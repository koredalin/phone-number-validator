<?php

namespace App\Repositories\Services;

use App\Repositories\Interfaces\TransactionRepositoryInterface;
use App\Common\Interfaces\DateTimeManagerInterface;
use App\Entities\Transaction;
use App\Entities\Email;
use App\Entities\Phone;

final class TransactionRepositoryService
{
    private $transactionRepository;
    private DateTimeManagerInterface $dtManager;
    
    public function __construct(TransactionRepositoryInterface $transactionRepository, DateTimeManagerInterface $dtManager){
        $this->transactionRepository = $transactionRepository;
        $this->dtManager = $dtManager;
    }
    
    public function findOneById(int $id): Transaction
    {
        return $this->transactionRepository->findOneById($id);
    }
    
    public function findOneByEmailPhone(Email $email, Phone $phone): Transaction
    {
        return $this->transactionRepository->findOneByUserName($email, $phone);
    }
    
    public function make(Email $email, Phone $phone): Transaction
    {
        $transaction = $this->transactionRepository->new();
        $transaction->email = $email;
        $transaction->phone = $phone;
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
    
    public function getOrCreateByEmailPhone(Email $email, Phone $phone): Transaction
    {
        $transactionObj = $this->findOneByEmailPhone($email, $phone);
        if (is_null($transactionObj)) {
            return $this->make($email, $phone);
        }
        
        return $transactionObj;
    }
}