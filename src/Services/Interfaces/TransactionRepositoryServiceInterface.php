<?php

namespace App\Services\Interfaces;

use App\Entities\User;
use App\Entities\Phone;
use App\Entities\Transaction;

/**
 *
 * @author Hristo
 */
interface TransactionRepositoryServiceInterface
{
    public function findOneById(int $id): ?Transaction;
    
    public function make(User $user, Phone $phone, string $rawPassword): Transaction;
    
    public function comparePassword(int $transactionId, string $comparisonPassword): Transaction;
}
