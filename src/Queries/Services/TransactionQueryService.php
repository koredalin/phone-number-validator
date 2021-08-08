<?php

namespace App\Queries\Services;

use App\Queries\Interfaces\PhoneConfirmationAttemptQueryInterface;
use App\Entities\Transaction;

final class TransactionQueryService
{
    private $transactionQuery;
    
    public function __construct(PhoneConfirmationAttemptQueryInterface $transactionQuery){
        $this->transactionQuery = $transactionQuery;
    }
    
    public function findOneById(int $id): Transaction
    {
        return $this->transactionQuery->findOneById($id);
    }
    
    public function findOneByUserName(string $transactionName): Transaction
    {
        return $this->transactionQuery->findOneByUserName($transactionName);
    }
    
    public function updateProduct(Product $product): void
    {
        $this->transactionQuery->save($product);
        // Dispatch some event on every update
    }
    
    public function all(): array
    {
        return $this->transactionQuery->all();
    }
}