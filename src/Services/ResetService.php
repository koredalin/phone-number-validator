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
// SMS
use App\Services\Interfaces\ConfirmationCodeSmsInterface;
// Response
use App\Controllers\ResponseStatuses as ResStatus;
// Exceptions
use Exception;
use App\Exceptions\AlreadyMadeServiceActionException;
use App\Exceptions\NotFoundTransactionException;
use App\Exceptions\AlreadyRegistratedTransactionException;
use App\Exceptions\ConfirmationResetCoolDownException;
use App\Exceptions\SMSConfirmationCodeNotSentException;

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
    private ConfirmationCodeSmsInterface $confirmationCodeSms;

    public function __construct(
        TransactionRepositoryInterface $transactionService,
        PhoneConfirmationRepositoryServiceInterface $phoneConfirmationService,
        DateTimeManagerInterface $dtManager,
        ConfirmationCodeSmsInterface $confirmationCodeSms
    ) {
        $this->transactionRepository = $transactionService;
        $this->phoneConfirmationService = $phoneConfirmationService;
        $this->dtManager = $dtManager;
        $this->confirmationCodeSms = $confirmationCodeSms;
        $this->setDefaultWebPageProperties();
    }
    
    
    public function resetConfirmationCode(int $transactionId): PhoneConfirmation
    {
        if ($this->isFinishedServiceAction) {
            throw new AlreadyMadeServiceActionException('The transaction confirmation reset is already made.');
        }
        $this->nextWebPage = self::CURRENT_WEB_PAGE_GROUP.'/'.$transactionId;
        $this->isFinishedServiceAction = true;
        
        $transaction = $this->findTransaction($transactionId);
        
        $phoneConfirmation = $this->findPhoneConfirmation($transaction);
        
        $this->actionCoolDown($transaction);
        
        $this->setPhoneConfirmationAbandoned($phoneConfirmation);
        
        $newPhoneConfirmation = $this->phoneConfirmationService->make($transaction);
        
        $newPhoneConfirmationWithSmsStatus = $this->actionSendSmsConfirmationCode($newPhoneConfirmation);
        
        $this->nextWebPage = self::NEXT_WEB_PAGE_GROUP.'/'.$transactionId;
        
        return $newPhoneConfirmationWithSmsStatus;
    }
    
    private function findTransaction(int $transactionId): Transaction
    {
        $transaction = $this->transactionRepository->findOneById($transactionId);
        if (is_null($transaction)) {
            throw new NotFoundTransactionException('Not found transaction.');
        }
        
        if ($transaction->getStatus() === Transaction::STATUS_CONFIRMED) {
            $this->nextWebPage = RC::TRANSACTION_INFO.'/'.(int)$transaction->getId();
            throw new AlreadyRegistratedTransactionException('Already confirmed transaction.');
        }
        
        return $transaction;
    }
    
    private function findPhoneConfirmation(Transaction $transaction): PhoneConfirmation
    {
        $phoneConfirmation = $this->phoneConfirmationService->findLastByTransactionAwaitingStatus($transaction);
        if (is_null($phoneConfirmation)) {
            throw new Exception('Not found phone code, number. '.$this->errors);
        }
        
        return $phoneConfirmation;
    }
    
    private function actionCoolDown(Transaction $transaction): void
    {
        if ($transaction->getCreatedAt()->add(new \DateInterval('PT'.self::MINUTES_BEFORE_RESET_START.'M')) > $this->dtManager->now()) {
            throw new ConfirmationResetCoolDownException('Minimum interval before confirmation code reset - '.self::MINUTES_BEFORE_RESET_START.' minutes. '.$this->errors);
        }
    }
    
    private function setPhoneConfirmationAbandoned(PhoneConfirmation $phoneConfirmation): void
    {
        $phoneConfirmation->setConfirmedAt(null);
        $phoneConfirmation->setStatus(PhoneConfirmation::STATUS_ABANDONED);
        $phoneConfirmation->setUpdatedAt($this->dtManager->now());
        $this->phoneConfirmationService->save($phoneConfirmation);
    }
    
    private function actionSendSmsConfirmationCode(PhoneConfirmation $phoneConfirmation): PhoneConfirmation
    {
        $phoneConfirmationWithSmsStatus = $this->confirmationCodeSms->sendConfirmationCodeMessage($phoneConfirmation->getId());
        if (is_null($phoneConfirmationWithSmsStatus) || $phoneConfirmationWithSmsStatus->getId() < 1) {
            $this->responseStatus = ResStatus::SERVICE_UNAVAILABLE;
            throw new SMSConfirmationCodeNotSentException(' Confirmation code SMS is not sent. '.$this->errors);
        }
        
        return $phoneConfirmationWithSmsStatus;
    }
}