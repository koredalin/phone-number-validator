<?php

namespace App\Services;

use App\Services\Interfaces\ConfirmationServiceInterface;
// Entities
use App\Entities\Transaction;
use App\Entities\PhoneConfirmation;
use App\Entities\PhoneConfirmationAttempt;
// Repositories
use App\Repositories\Interfaces\TransactionRepositoryInterface;
use App\Repositories\Interfaces\PhoneConfirmationRepositoryInterface;
// Repository Services
use App\Services\Interfaces\PhoneConfirmationAttemptRepositoryServiceInterface;
use App\Common\Interfaces\DateTimeManagerInterface;

/**
 * Phone number cofirmation by confirmation code
 *
 * @author Hristo
 */
class ConfirmationService implements ConfirmationServiceInterface
{
    const CURRENT_WEB_PAGE_GROUP = '/confirmation';
    const NEXT_WEB_PAGE_GROUP = '/success';
    
    private TransactionRepositoryInterface $transactionRepository;
    private PhoneConfirmationRepositoryInterface $phoneConfirmationRepository;
    private PhoneConfirmationAttemptRepositoryServiceInterface $phoneConfirmationAttemptService;
    private DateTimeManagerInterface $dtManager;
    
    private string $anyError;
    private string $dbError;
    
    private bool $isFinishedConfirmation;
    
    private string $nextWebPage;
    
    private bool $isSuccess;


    public function __construct(
        TransactionRepositoryInterface $transactionService,
        PhoneConfirmationRepositoryInterface $phoneConfirmationService,
        PhoneConfirmationAttemptRepositoryServiceInterface $phoneConfirmationAttemptService,
        DateTimeManagerInterface $dtManager
    ) {
        $this->transactionRepository = $transactionService;
        $this->phoneConfirmationRepository = $phoneConfirmationService;
        $this->phoneConfirmationAttemptService = $phoneConfirmationAttemptService;
        $this->dtManager = $dtManager;
        $this->anyError = '';
        $this->dbError = '';
        $this->isFinishedConfirmation = false;
        $this->nextWebPage = '';
        $this->isSuccess = false;
    }
    
    public function confirmCode(int $transactionId, string $requestBody): ?PhoneConfirmationAttempt
    {
        if ($this->isFinishedConfirmation) {
            throw new \Exception('The registration is already made.');
        }
        
        $parsedRequestBody = \json_decode($requestBody, true);
        $transaction = $this->transactionRepository->findOneById($transactionId);
        if (is_null($transaction)) {
            $this->anyError = 'Not found transaction.';
            return null;
        }
        
        $phoneConfirmation = $this->phoneConfirmationRepository->findLastByTransactionAwaitingStatus($transaction);
        if (is_null($phoneConfirmation)) {
            $this->anyError = 'Not found phone code.';
            return null;
        }
        
        $inputConfirmationCode = (int)$parsedRequestBody['confirmationCode'];
        $phoneConfirmationAttempt = $this->phoneConfirmationAttemptService->createByPhoneConfirmationInputConfirmationCode($phoneConfirmation, $inputConfirmationCode);
        
        $this->isFinishedConfirmation = true;
        if (
            $phoneConfirmationAttempt instanceof PhoneConfirmationAttempt
            && $phoneConfirmationAttempt->getStatus() === PhoneConfirmationAttempt::STATUS_CONFIRMED
        ) {
            $this->isSuccess = true;
            $this->anyError = 'Wrong confirmation code.';
            $this->nextWebPage = self::NEXT_WEB_PAGE_GROUP.'/'.$transactionId;
            $this->setPhoneConfirmationSuccess($phoneConfirmation);
            $this->setTransactionSuccess($transaction);
        } else {
            $this->isSuccess = false;
            $this->nextWebPage = self::CURRENT_WEB_PAGE_GROUP.'/'.$transactionId;
        }
        
        return $phoneConfirmationAttempt;
    }
    
    public function getAnyError(): string
    {
        return $this->anyError;
    }
    
    /**
     * Returns the errors when a new registration is not recorded into the database.
     * 
     * @return type
     */
    public function getDatabaseErrors(): string
    {
        return $this->dbError;
    }
    
    public function isSuccess(): string
    {
        return $this->isSuccess;
    }
    
    public function getNextWebPage(): string
    {
        if (!$this->isFinishedConfirmation) {
            throw new \Exception('The confirmation is not finished.');
        }
        
        return $this->nextWebPage;
    }
    
    private function setPhoneConfirmationSuccess(PhoneConfirmation $phoneConfirmation): void
    {
        $phoneConfirmation->setConfirmedAt($this->dtManager->now());
        $phoneConfirmation->setStatus(PhoneConfirmation::STATUS_CONFIRMED);
        $phoneConfirmation->setUpdatedAt($this->dtManager->now());
        $this->phoneConfirmationRepository->save($phoneConfirmation);
    }
    
    private function setTransactionSuccess(Transaction $transaction): void
    {
        $transaction->setConfirmedAt($this->dtManager->now());
        $transaction->setStatus(Transaction::STATUS_CONFIRMED);
        $transaction->setUpdatedAt($this->dtManager->now());
        $this->transactionRepository->save($transaction);
    }
}
