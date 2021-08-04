<?php

namespace App\Repositories\Interfaces;

use App\Entities\Phone;
use App\Entities\Country;

/**
 *
 * @author Hristo
 */
interface PhoneRepositoryInterface
{
    /**
     * Returns an empty instance of the entity class.
     */
    public function new(): Phone;
    
    public function findOneById(int $id): Phone;
    
    public function findByOnePhoneCodeNumber(Country $country, int $phoneNumber): ?Phone;
    
    public function save(Phone $user): Phone;
    
    public function getDatabaseException(): string;
}
