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
    
    public function getDatabaseException(): string;
    
}
