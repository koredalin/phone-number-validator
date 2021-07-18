<?php

namespace App\Repositories\Interfaces;

use App\Entities\Phone;

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
    
    public function findByOnePhoneCodeNumber(int $phoneCode, int $phoneNumber): Phone;
    
    public function save(Phone $user): void;
}
