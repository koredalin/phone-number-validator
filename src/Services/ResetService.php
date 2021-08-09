<?php

namespace App\Services;

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

/**
 * Reset confirmation code (eventually password) service.
 *
 * @author Hristo
 */
class ResetService implements ResetServiceInterface
{
    const CURRENT_WEB_PAGE_GROUP = RC::RESET_CODE;
    const NEXT_WEB_PAGE_GROUP = RC::CONFIRMATION;
    
    private TransactionRepositoryInterface $transactionRepository;
    private PhoneConfirmationRepositoryServiceInterface $phoneConfirmationService;
    private DateTimeManagerInterface $dtManager;
    
    private string $errors;
    
    private bool $isFinishedReset;
    
    private string $nextWebPage;
    
    private bool $isSuccess;

    public function __construct(
        TransactionRepositoryInterface $transactionService,
        PhoneConfirmationRepositoryServiceInterface $phoneConfirmationService,
        DateTimeManagerInterface $dtManager
    ) {
        $this->transactionRepository = $transactionService;
        $this->phoneConfirmationService = $phoneConfirmationService;
        $this->dtManager = $dtManager;
        $this->errors = '';
        $this->isFinishedReset = false;
        $this->nextWebPage = '';
        $this->isSuccess = false;
    }
    
    
    public function resetConfirmationCode(int $transactionId): ?PhoneConfirmation
    {
        if ($this->isFinishedReset) {
            throw new \Exception('The registration is already made.');
        }
        $this->nextWebPage = self::CURRENT_WEB_PAGE_GROUP.'/'.$transactionId;
        $this->isFinishedReset = true;
        $this->isSuccess = false;
        
        $transaction = $this->transactionRepository->findOneById($transactionId);
        if (is_null($transaction)) {
            $this->errors .= 'Not found transaction.';
            return null;
        }
        
        if ($transaction->getStatus() === Transaction::STATUS_CONFIRMED) {
            $this->errors .= 'Already confirmed transaction.';
            $this->nextWebPage = RC::SUCCESS.'/'.$transactionId;
            $this->isSuccess = true;
            return null;
        }
        
        $phoneConfirmation = $this->phoneConfirmationService->findLastByTransactionAwaitingStatus($transaction);
        if (is_null($phoneConfirmation)) {
            $this->errors .= 'Not found phone code.';
            return null;
        }
        
        $this->setPhoneConfirmationAbandoned($phoneConfirmation);
        $newPhoneConfirmation = $this->phoneConfirmationService->make($transaction);
        if ($transaction->getCreatedAt()->add(new \DateInterval('PT'.self::COOL_DOWN_MINUTES.'M')) > $this->dtManager->now()) {
            $this->errors .= 'Minimum interval before confirmation code reset - '.self::COOL_DOWN_MINUTES.' minutes.';
            return null;
        }
        
        $this->nextWebPage = self::NEXT_WEB_PAGE_GROUP.'/'.$transactionId;
        $this->isSuccess = true;
        
        return $newPhoneConfirmation;
    }
    
    public function getErrors(): string
    {
        return $this->errors;
    }
    
    public function isSuccess(): string
    {
        return $this->isSuccess;
    }
    
    public function getNextWebPage(): string
    {
        if (!$this->isFinishedReset) {
            throw new \Exception('The confirmation is not finished.');
        }
        
        return $this->nextWebPage;
    }
    
    private function setPhoneConfirmationAbandoned(PhoneConfirmation $phoneConfirmation): void
    {
        $phoneConfirmation->setConfirmedAt(null);
        $phoneConfirmation->setStatus(PhoneConfirmation::STATUS_ABANDONED);
        $phoneConfirmation->setUpdatedAt($this->dtManager->now());
        $this->phoneConfirmationService->save($phoneConfirmation);
    }
}