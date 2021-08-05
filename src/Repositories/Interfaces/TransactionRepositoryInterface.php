<?php

namespace App\Repositories\Interfaces;

use App\Entities\Transaction;
use App\Entities\User;
use App\Entities\Phone;

/**
 * @author Hristo
 */
interface TransactionRepositoryInterface
{
    /**
     * Returns an empty instance of the entity class.
     */
    public function new(): Transaction;
    
    public function findOneById(int $id): ?Transaction;
    
    public function save(Transaction $transaction): Transaction;
    
    public function getDatabaseException(): string;
}
