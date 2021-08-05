<?php

namespace App\Services\Interfaces;

use App\Entities\PhoneConfirmation;
use App\Entities\PhoneConfirmationAttempt;

/**
 *
 * @author Hristo
 */
interface PhoneConfirmationAttemptRepositoryServiceInterface
{
    public function findOneById(int $id): ?PhoneConfirmationAttempt;
    
    public function createByPhoneConfirmationIsConfirmedCode(PhoneConfirmation $phoneConfirmation, bool $isConfirmedCode): PhoneConfirmationAttempt;
    
    public function getDatabaseException(): string;
}
