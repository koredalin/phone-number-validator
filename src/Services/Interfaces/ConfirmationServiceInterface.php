<?php

namespace App\Services\Interfaces;

use App\Entities\PhoneConfirmationAttempt;

/**
 *
 * @author Hristo
 */
interface ConfirmationServiceInterface
{
    public function confirmCode(int $transactionId, string $requestBody): ?PhoneConfirmationAttempt;
    
    public function getErrors(): string;
    
    public function getNextWebPage(): string;
    
    public function isSuccess(): string;
}
