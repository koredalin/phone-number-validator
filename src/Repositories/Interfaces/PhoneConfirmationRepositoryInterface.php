<?php

namespace App\Repositories\Interfaces;

use App\Entities\PhoneConfirmation;

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
    
    public function findOneByUserIdValidationCode(int $userId, int $validationCode): PhoneConfirmation;
    
    public function save(PhoneConfirmation $phoneConfirmation): void;
}
