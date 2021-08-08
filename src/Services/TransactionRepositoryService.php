<?php

namespace App\Services;

use App\Services\Interfaces\TransactionRepositoryServiceInterface;
use App\Repositories\Interfaces\TransactionRepositoryInterface;
use App\Common\Interfaces\DateTimeManagerInterface;
use App\Common\Interfaces\PasswordGeneratorInterface;
use App\Entities\User;
use App\Entities\Phone;
use App\Entities\Transaction;

final class TransactionRepositoryService implements TransactionRepositoryServiceInterface
{
    private TransactionRepositoryInterface $transactionRepository;
    private PasswordGeneratorInterface $passwordGenerator;
    private DateTimeManagerInterface $dtManager;
    private string $dbException;
    
    public function __construct(
        TransactionRepositoryInterface $transactionRepository,
        PasswordGeneratorInterface $passwordGenerator,
        DateTimeManagerInterface $dtManager
    ){
        $this->transactionRepository = $transactionRepository;
        $this->passwordGenerator = $passwordGenerator;
        $this->dtManager = $dtManager;
        $this->dbException = '';
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
        $transaction->setCreatedAt($this->dtManager->now());
        $transaction->setUpdatedAt($this->dtManager->now());
        
        return $this->save($transaction);
    }
    
    private function save(Transaction $transaction): Transaction
    {
        $savedTransaction = $this->transactionRepository->save($transaction);
        
        return $savedTransaction;
    }
    
    public function getDatabaseException(): string
    {
        return $this->transactionRepository->getDatabaseException();
    }
    
//    public function all(): array
//    {
//        return $this->transactionRepository->all();
//    }
}