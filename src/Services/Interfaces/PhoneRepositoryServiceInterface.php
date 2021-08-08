<?php

namespace App\Services\Interfaces;

use App\Entities\Phone;
use App\Entities\Country;

/**
 *
 * @author Hristo
 */
interface PhoneRepositoryServiceInterface
{
    public function getOrCreateByAssembledPhoneNumber(string $assembledPhoneNumber): ?Phone;
    
    public function findOneById(int $id): ?Phone;
    
    public function findByOnePhoneCodeNumber(Country $country, int $phoneNumber): ?Phone;
    
    public function getAnyError(): string;
}
