<?php

namespace App\Services\Interfaces;

use Doctrine\Common\Collections\Collection;
use App\Entities\PhoneConfirmation;
use App\Entities\PhoneConfirmationAttempt;

/**
 *
 * @author Hristo
 */
interface PhoneConfirmationAttemptRepositoryServiceInterface
{
    public function findOneById(int $id): ?PhoneConfirmationAttempt;
    
    public function createByPhoneConfirmationInputConfirmationCode(PhoneConfirmation $phoneConfirmation, int $inputConfirmationCode, bool $isCoolDown = false): PhoneConfirmationAttempt;
    
    public function findAllByPhoneConfirmationNoCoolDownDesc(PhoneConfirmation $phoneConfirmation): Collection;
}
