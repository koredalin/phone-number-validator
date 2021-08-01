<?php

namespace App\Services\Interfaces;

/**
 *
 * @author Hristo
 */
interface TransactionRepositoryServiceInterface
{
    public function getOrCreateByEmailPhoneAwaitingStatus(User $email, Phone $phone): Transaction;
    
    public function findOneById(int $id): Transaction;
}
