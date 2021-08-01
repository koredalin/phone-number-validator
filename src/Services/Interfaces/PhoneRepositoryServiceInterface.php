<?php

namespace App\Services\Interfaces;

use App\Entities\Phone;

/**
 *
 * @author Hristo
 */
interface PhoneRepositoryServiceInterface
{
    public function getOrCreateByAssembledPhoneNumber(string $assembledPhoneNumber): Phone;
    
    public function findOneById(int $id): Phone;
    
    public function findByOnePhoneCodeNumber(int $phoneCode, int $phoneNumber): Phone;
    
    public function getDoctrineException(): string;
}
