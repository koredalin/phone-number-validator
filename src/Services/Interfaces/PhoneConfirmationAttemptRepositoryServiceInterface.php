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
    
    public function createByPhoneConfirmationInputConfirmationCode(PhoneConfirmation $phoneConfirmation, int $inputConfirmationCode): PhoneConfirmationAttempt;
    
    public function findAllByPhoneConfirmation(PhoneConfirmation $phoneConfirmation): array;
}
