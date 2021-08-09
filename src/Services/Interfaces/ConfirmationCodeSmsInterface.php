<?php

namespace App\Services\Interfaces;

use App\Entities\PhoneConfirmation;

/**
 *
 * @author Hristo
 */
interface ConfirmationCodeSmsInterface
{
    public function sendConfirmationCodeMessage(int $phoneConfirmationId): PhoneConfirmation;
}
