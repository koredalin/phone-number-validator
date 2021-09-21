<?php

namespace App\Services;

use App\Services\Interfaces\TransactionRepositoryServiceInterface;
use App\Services\Interfaces\SuccessSmsInterface;
use App\Repositories\Interfaces\TransactionRepositoryInterface;
use App\Common\Interfaces\DateTimeManagerInterface;
use App\Common\Interfaces\PasswordGeneratorInterface;
use App\Entities\User;
use App\Entities\Phone;
use App\Entities\Transaction;
// Exceptions
use App\Exceptions\NotFoundTransactionException;
use App\Exceptions\WrongTransactionIdOrPasswordException;

final class TransactionRepositoryService implements TransactionRepositoryServiceInterface, SuccessSmsInterface
{
    const SUCCESS_SMS_MESSAGE = 'Successful transaction.';
    
    private TransactionRepositoryInterface $transactionRepository;
    private PasswordGeneratorInterface $passwordGenerator;
    private DateTimeManagerInterface $dtManager;
    
    public function __construct(
        TransactionRepositoryInterface $transactionRepository,
        PasswordGeneratorInterface $passwordGenerator,
        DateTimeManagerInterface $dtManager
    ){
        $this->transactionRepository = $transactionRepository;
        $this->passwordGenerator = $passwordGenerator;
        $this->dtManager = $dtManager;
    }
    
    public function findOneById(int $id): ?Transaction
    {
        return $this->transactionRepository->findOneById($id);
    }
    
    public function make(User $user, Phone $phone, string $rawPassword): Transaction
    {
        $transaction = $this->transactionRepository->new();
        $transaction->setUser($user);
        $transaction->setPhone($phone);
        $transaction->setStatus(Transaction::STATUS_AWAITING_REQUEST);
        $transaction->setPassword($this->passwordGenerator->encode($rawPassword));
        $transaction->setConfirmedAt(null);
        $transaction->setSuccessMessage('');
        $transaction->setCreatedAt($this->dtManager->now());
        $transaction->setUpdatedAt($this->dtManager->now());
        
        return $this->save($transaction);
    }
    
    public function comparePassword(int $transactionId, string $comparisonPassword): Transaction
    {
        $transaction = $this->findOneById($transactionId);
        if (is_null($transaction)) {
            throw new NotFoundTransactionException((string)$transactionId);
        }
        
        if ($transaction->getPassword() !== $this->passwordGenerator->encode($comparisonPassword)) {
            throw new WrongTransactionIdOrPasswordException('');
        }
        
        return $transaction;
    }
    
    private function save(Transaction $transaction): Transaction
    {
        $savedTransaction = $this->transactionRepository->save($transaction);
        
        return $savedTransaction;
    }
    
    public function sendSuccessMessage(int $transactionId): Transaction
    {
        $transaction = $this->findOneById($transactionId);
        if (is_null($transaction)) {
            throw new \Exception('sendSuccessMessage() is for Transaction objects already recorded into the database.');
        }
        
        $transaction->setSuccessMessage(self::SUCCESS_SMS_MESSAGE);
        $transaction->setUpdatedAt($this->dtManager->now());
        
        return $this->save($transaction);
    }
}