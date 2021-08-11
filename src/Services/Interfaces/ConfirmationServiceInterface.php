<?php

namespace App\Services\Interfaces;

use App\Services\Interfaces\WebPageServiceInterface;
use App\Entities\PhoneConfirmationAttempt;

/**
 *
 * @author Hristo
 */
interface ConfirmationServiceInterface extends WebPageServiceInterface
{
    public function confirmCode(int $transactionId, string $requestBody): ?PhoneConfirmationAttempt;
}
