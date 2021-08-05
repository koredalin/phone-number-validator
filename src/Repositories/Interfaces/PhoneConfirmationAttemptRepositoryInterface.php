<?php

namespace App\Repositories\Interfaces;

use App\Entities\PhoneConfirmationAttempt;

/**
 *
 * @author Hristo
 */
interface PhoneConfirmationAttemptRepositoryInterface
{
    /**
     * Returns an empty instance of the entity class.
     */
    public function new(): PhoneConfirmationAttempt;
    
    public function findOneById(int $id): PhoneConfirmationAttempt;
    
    public function save(PhoneConfirmationAttempt $phoneConfirmationAttempt): PhoneConfirmationAttempt;
    
    public function getDatabaseException(): string;
}
