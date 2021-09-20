<?php

namespace App\Services\Interfaces;

use App\Services\Interfaces\WebPageServiceInterface;
use App\Entities\PhoneConfirmationAttempt;
// Input
use App\Controllers\Input\Models\ConfirmationCodeModel;

/**
 *
 * @author Hristo
 */
interface ConfirmationServiceInterface extends WebPageServiceInterface
{
    public function confirmCode(int $transactionId, ConfirmationCodeModel $confirmationCodeModel): PhoneConfirmationAttempt;
}
