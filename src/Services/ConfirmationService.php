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
    
    private string $anyError;
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
        $this->anyError = '';
        $this->dbError = '';
        $this->isFinishedConfirmation = false;
        $this->nextWebPage = '';
    }
    
    public function confirmCode(int $transactionId, string $requestBody): ?PhoneConfirmationAttempt
    {
//        echo $transactionId.' ||||||||| '.$requestBody; exit;
        $parsedRequestBody = \json_decode($requestBody, true);
        $transaction = $this->transactionService->findOneById($transactionId);
        print_r($transaction); exit;
        if (is_null($transaction)) {
            $this->anyError = 'Not found transaction.';
            return null;
        }
        
        $phoneConfirmation = $this->phoneConfirmationService->findLastByTransactionAwaitingStatus($transaction);
        print_r($phoneConfirmation); exit;
        if (is_null($phoneConfirmation)) {
            $this->anyError = 'Not found phone code.';
            return null;
        }
        
        $inputConfirmationCode = (int)$parsedRequestBody['confirmationCode'];
        $caliberConfirmationCode = (int)$phoneConfirmation->getConfirmationCode();
        $isConfirmedCode = $inputConfirmationCode == $caliberConfirmationCode;
        $phoneConfirmationAttempt = $this->phoneConfirmationAttemptService->createByPhoneConfirmationIsConfirmedCode($phoneConfirmation, $isConfirmedCode);
        
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
    
    public function getNextWebPage(): string
    {
        if (!$this->isFinishedConfirmation) {
            throw new \Exception('The confirmation is not finished.');
        }
        
        return $this->nextWebPage;
    }
}
