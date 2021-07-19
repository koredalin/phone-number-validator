<?php

namespace App\Repositories\Interfaces;

use App\Entities\PhoneConfirmation;

use App\Entities\Transaction;

/**
 *
 * @author Hristo
 */
interface PhoneConfirmationRepositoryInterface
{
    /**
     * Returns an empty instance of the entity class.
     */
    public function new(): PhoneConfirmation;
    
    public function findOneById(int $id): PhoneConfirmation;
    
    public function findLastByTransactionAwaitingStatus(Transaction $transaction): ?PhoneConfirmation;
    
    public function save(PhoneConfirmation $phoneConfirmation): void;
}
