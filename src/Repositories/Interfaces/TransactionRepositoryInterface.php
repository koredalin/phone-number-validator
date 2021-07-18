<?php

namespace App\Repositories\Interfaces;

use App\Entities\Transaction;
use App\Entities\Email;
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
    
    public function findOneById(int $id): Transaction;
    
    public function findOneByEmailPhone(Email $email, Phone $phone): Transaction;
    
    public function save(Transaction $transaction): void;
}
