<?php

namespace App\Services;

use App\Services\Interfaces\ConfirmationServiceInterface;
// Entities
use App\Entities\PhoneConfirmationAttempt;
// Repository Services
use App\Services\Interfaces\TransactionRepositoryServiceInterface;
use App\Services\Interfaces\PhoneConfirmationRepositoryServiceInterface;
use App\Services\Interfaces\PhoneConfirmationAttemptRepositoryServiceInterface;

/**
 * Phone number cofirmation by confirmation code
 *
 * @author Hristo
 */
class ConfirmationService implements ConfirmationServiceInterface
{
    const NEXT_WEB_PAGE_GROUP = '/success';
    
    private TransactionRepositoryServiceInterface $transactionService;
    private PhoneConfirmationRepositoryServiceInterface $phoneConfirmationService;
    private PhoneConfirmationAttemptRepositoryServiceInterface $phoneConfirmationAttemptService;
    
    private string $dbError;
    
    private bool $isFinishedConfirmation;
    
    private string $nextWebPage;


    public function __construct(
        TransactionRepositoryServiceInterface $transactionService,
        PhoneConfirmationRepositoryServiceInterface $phoneConfirmationService,
        PhoneConfirmationAttemptRepositoryServiceInterface $phoneConfirmationAttemptService
    ) {
        $this->transactionService = $transactionService;
        $this->phoneConfirmationService = $phoneConfirmationService;
        $this->phoneConfirmationAttemptService = $phoneConfirmationAttemptService;
        $this->dbError = '';
        $this->isFinishedConfirmation = false;
        $this->nextWebPage = '';
    }
    
    public function confirmCode(int $transactionId, string $requestBody): ?PhoneConfirmationAttempt
    {
        $parsedRequestBody = \json_decode($requestBody, true);
        $inputConfirmationCode = (int)$parsedRequestBody['confirmationCode'];
        $transaction = $this->transactionService->findOneById($transactionId);
        $phoneConfirmation = $this->phoneConfirmationService->findLastByTransactionAwaitingStatus($transaction);
        $caliberConfirmationCode = (int)$phoneConfirmation->getConfirmationCode();
        $isConfirmedCode = $inputConfirmationCode == $caliberConfirmationCode;
        
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
    
    public function getNextWebPage(): string
    {
        if (!$this->isFinishedConfirmation) {
            throw new \Exception('The confirmation is not finished.');
        }
        
        return $this->nextWebPage;
    }
}
