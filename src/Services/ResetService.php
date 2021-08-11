<?php

namespace App\Services;

use App\Services\WebPageService;
use App\Services\Interfaces\ResetServiceInterface;
use App\Controllers\RouteConstants as RC;
// Entities
use App\Entities\Transaction;
use App\Entities\PhoneConfirmation;
// Repositories
use App\Repositories\Interfaces\TransactionRepositoryInterface;
use App\Services\Interfaces\PhoneConfirmationRepositoryServiceInterface;
// Repository Services
use App\Common\Interfaces\DateTimeManagerInterface;
// Response
use App\Controllers\ResponseStatuses as ResStatus;

/**
 * Reset confirmation code (eventually password) service.
 *
 * @author Hristo
 */
class ResetService extends WebPageService implements ResetServiceInterface
{
    const CURRENT_WEB_PAGE_GROUP = RC::RESET_CODE;
    const NEXT_WEB_PAGE_GROUP = RC::CONFIRMATION;
    const MINUTES_BEFORE_RESET_START = 1;
    
    private TransactionRepositoryInterface $transactionRepository;
    private PhoneConfirmationRepositoryServiceInterface $phoneConfirmationService;
    private DateTimeManagerInterface $dtManager;

    public function __construct(
        TransactionRepositoryInterface $transactionService,
        PhoneConfirmationRepositoryServiceInterface $phoneConfirmationService,
        DateTimeManagerInterface $dtManager
    ) {
        $this->transactionRepository = $transactionService;
        $this->phoneConfirmationService = $phoneConfirmationService;
        $this->dtManager = $dtManager;
        $this->setDefaultWebPageProperties();
    }
    
    
    public function resetConfirmationCode(int $transactionId): ?PhoneConfirmation
    {
        if ($this->isFinishedServiceAction) {
            throw new \Exception('The registration is already made.');
        }
        $this->nextWebPage = self::CURRENT_WEB_PAGE_GROUP.'/'.$transactionId;
        $this->isFinishedServiceAction = true;
        $this->isSuccess = false;
        
        $transaction = $this->transactionRepository->findOneById($transactionId);
        if (is_null($transaction)) {
            $this->responseStatus = ResStatus::NOT_FOUND;
            $this->errors .= 'Not found transaction.';
            return null;
        }
        
        if ($transaction->getStatus() === Transaction::STATUS_CONFIRMED) {
            $this->responseStatus = ResStatus::ALREADY_REPORTED;
            $this->errors .= 'Already confirmed transaction.';
            $this->nextWebPage = RC::SUCCESS.'/'.$transactionId;
            $this->isSuccess = true;
            return null;
        }
        
        $phoneConfirmation = $this->phoneConfirmationService->findLastByTransactionAwaitingStatus($transaction);
        if (is_null($phoneConfirmation)) {
            $this->responseStatus = ResStatus::SERVICE_UNAVAILABLE;
            $this->errors .= 'Not found phone code, number.';
            return null;
        }
        
        if ($transaction->getCreatedAt()->add(new \DateInterval('PT'.self::MINUTES_BEFORE_RESET_START.'M')) > $this->dtManager->now()) {
            $this->responseStatus = ResStatus::FORBIDDEN;
            $this->errors .= 'Minimum interval before confirmation code reset - '.self::MINUTES_BEFORE_RESET_START.' minutes.';
            return null;
        }
        
        $this->setPhoneConfirmationAbandoned($phoneConfirmation);
        $newPhoneConfirmation = $this->phoneConfirmationService->make($transaction);
        $this->nextWebPage = self::NEXT_WEB_PAGE_GROUP.'/'.$transactionId;
        $this->isSuccess = true;
        
        return $newPhoneConfirmation;
    }
    
    private function setPhoneConfirmationAbandoned(PhoneConfirmation $phoneConfirmation): void
    {
        $phoneConfirmation->setConfirmedAt(null);
        $phoneConfirmation->setStatus(PhoneConfirmation::STATUS_ABANDONED);
        $phoneConfirmation->setUpdatedAt($this->dtManager->now());
        $this->phoneConfirmationService->save($phoneConfirmation);
    }
}