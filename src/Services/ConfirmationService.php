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
 * Phone number confirmation by confirmation code
 *
 * @author Hristo
 */
class ConfirmationService implements ConfirmationServiceInterface
{
    const CURRENT_WEB_PAGE_GROUP = '/confirmation';
    const NEXT_WEB_PAGE_GROUP = '/success';
    const COOL_DOWN_CONFIRMATION_ATTEMPTS_NUMBER = 3;
    const COOL_DOWN_MINUTES = 1;
    
    private TransactionRepositoryInterface $transactionRepository;
    private PhoneConfirmationRepositoryInterface $phoneConfirmationRepository;
    private PhoneConfirmationAttemptRepositoryServiceInterface $phoneConfirmationAttemptService;
    private DateTimeManagerInterface $dtManager;
    
    private string $errors;
    
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
        $this->errors = '';
        $this->isFinishedConfirmation = false;
        $this->nextWebPage = '';
        $this->isSuccess = false;
    }
    
    public function confirmCode(int $transactionId, string $requestBody): ?PhoneConfirmationAttempt
    {
        if ($this->isFinishedConfirmation) {
            throw new \Exception('The registration is already made.');
        }
        $this->nextWebPage = self::CURRENT_WEB_PAGE_GROUP.'/'.$transactionId;
        $this->isFinishedConfirmation = true;
        
        $parsedRequestBody = \json_decode($requestBody, true);
        $transaction = $this->transactionRepository->findOneById($transactionId);
        if (is_null($transaction)) {
            $this->errors .= 'Not found transaction.';
            return null;
        }
        
        if ($transaction->getStatus() === Transaction::STATUS_CONFIRMED) {
            $this->errors .= 'Already confirmed transaction.';
            $this->nextWebPage = self::NEXT_WEB_PAGE_GROUP.'/'.$transactionId;
            $this->isSuccess = true;
            return null;
        }
        
        $phoneConfirmation = $this->phoneConfirmationRepository->findLastByTransactionAwaitingStatus($transaction);
        if (is_null($phoneConfirmation)) {
            $this->errors .= 'Not found phone code.';
            return null;
        }
        
        if ($phoneConfirmation->getStatus() === PhoneConfirmation::STATUS_ABANDONED) {
            throw new \Exception('PhoneConfirmation status abandoned is not possible in such cases. transactionId: '.$transactionId.'. phoneConfirmationId: '.$phoneConfirmation->getId().'.');
        }
        
        $inputConfirmationCode = (int)$parsedRequestBody['confirmationCode'];
        $phoneConfirmationAttempts = $this->phoneConfirmationAttemptService->findAllByPhoneConfirmationNoCoolDownDesc($phoneConfirmation);
        $lastAttemptTime = count($phoneConfirmationAttempts) > 0 ? $phoneConfirmationAttempts->first()->getCreatedAt() : null;
        if (
            count($phoneConfirmationAttempts) > 1
            && count($phoneConfirmationAttempts) % self::COOL_DOWN_CONFIRMATION_ATTEMPTS_NUMBER == 0
            && $lastAttemptTime->add(new \DateInterval('PT'.self::COOL_DOWN_MINUTES.'M')) > $this->dtManager->now()
        ) {
            $this->phoneConfirmationAttemptService->createByPhoneConfirmationInputConfirmationCode($phoneConfirmation, $inputConfirmationCode, true);
            $this->errors .= 'Minimum interval before next confirmation code attempt: '.self::COOL_DOWN_MINUTES.' minutes.';
            return null;
        }
        
        $phoneConfirmationAttempt = $this->phoneConfirmationAttemptService->createByPhoneConfirmationInputConfirmationCode($phoneConfirmation, $inputConfirmationCode);
        if (
            $phoneConfirmationAttempt instanceof PhoneConfirmationAttempt
            && $phoneConfirmationAttempt->getStatus() === PhoneConfirmationAttempt::STATUS_CONFIRMED
        ) {
            $this->isSuccess = true;
            $this->nextWebPage = self::NEXT_WEB_PAGE_GROUP.'/'.$transactionId;
            $this->setPhoneConfirmationSuccess($phoneConfirmation);
            $this->setTransactionSuccess($transaction);
        } else {
            $this->isSuccess = false;
            $this->errors .= 'Wrong confirmation code.';
        }
        
        return $phoneConfirmationAttempt;
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