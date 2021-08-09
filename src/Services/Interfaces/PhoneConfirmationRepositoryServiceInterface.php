<?php

namespace App\Services\Interfaces;

use App\Entities\Transaction;
use App\Entities\PhoneConfirmation;

/**
 *
 * @author Hristo
 */
interface PhoneConfirmationRepositoryServiceInterface
{
    public function findOneById(int $id): ?PhoneConfirmation;
    
    public function findLastByTransactionAwaitingStatus(Transaction $transaction): ?PhoneConfirmation;
    
    public function getOrCreateByTransactionAwaitingStatus(Transaction $transaction): PhoneConfirmation;
    
    /**
     * Makes new PhoneConfirmation entity with status AWAITING_REQUEST and saves it into the database.
     * @param Transaction $transaction
     * @return PhoneConfirmation
     */
    public function make(Transaction $transaction): PhoneConfirmation;
    
    public function save(PhoneConfirmation $phoneConfirmation): PhoneConfirmation;
}
