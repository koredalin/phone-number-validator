<?php

namespace App\Services\Interfaces;

use App\Services\Interfaces\WebPageServiceInterface;
use App\Entities\PhoneConfirmation;

/**
 *
 * @author Hristo
 */
interface ResetServiceInterface extends WebPageServiceInterface
{
    public function resetConfirmationCode(int $transactionId): ?PhoneConfirmation;
}