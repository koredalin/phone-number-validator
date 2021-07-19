<?php

namespace App\Repositories\Services;

use App\Repositories\Interfaces\TransactionRepositoryInterface;
use App\Common\Interfaces\DateTimeManagerInterface;
use App\Common\Interfaces\PasswordGeneratorInterface;
use App\Entities\Transaction;
use App\Entities\User;
use App\Entities\Phone;

final class TransactionRepositoryService
{
    private $transactionRepository;
    private PasswordGeneratorInterface $passwordGenerator;
    private DateTimeManagerInterface $dtManager;
    
    public function __construct(TransactionRepositoryInterface $transactionRepository, PasswordGeneratorInterface $passwordGenerator, DateTimeManagerInterface $dtManager){
        $this->transactionRepository = $transactionRepository;
        $this->passwordGenerator = $passwordGenerator;
        $this->dtManager = $dtManager;
    }
    
    public function findOneById(int $id): Transaction
    {
        return $this->transactionRepository->findOneById($id);
    }
    
    public function findOneByEmailPhoneAwaitingStatus(User $email, Phone $phone): Transaction
    {
        return $this->transactionRepository->findOneByEmailPhoneAwaitingStatus($email, $phone);
    }
    
    public function make(User $email, Phone $phone, string $nonCryptedPassword): Transaction
    {
        $transaction = $this->transactionRepository->new();
        $transaction->email = $email;
        $transaction->phone = $phone;
        $transaction->status = Transaction::STATUS_AWAITING_REQUEST;
        $transaction->password = $this->passwordGenerator->encode($nonCryptedPassword);
        $transaction->createdAt = $this->dtManager->now();
        $transaction->updatedAt = $this->dtManager->now();
        
        return $this->save($transaction);
    }
    
    public function save(Transaction $transaction): void
    {
        $this->transactionRepository->save($transaction);
        // Dispatch some event on every update
    }
    
    public function all(): array
    {
        return $this->transactionRepository->all();
    }
    
    public function getOrCreateByEmailPhoneAwaitingStatus(User $email, Phone $phone): Transaction
    {
        $transactionObj = $this->findOneByEmailPhoneAwaitingStatus($email, $phone);
        if (is_null($transactionObj)) {
            return $this->make($email, $phone);
        }
        
        return $transactionObj;
    }
}