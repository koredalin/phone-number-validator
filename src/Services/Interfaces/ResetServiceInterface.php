<?php

namespace App\Services\Interfaces;

use App\Entities\PhoneConfirmation;

/**
 *
 * @author Hristo
 */
interface ResetServiceInterface
{
    public function resetConfirmationCode(int $transactionId): ?PhoneConfirmation;
    
    public function getErrors(): string;
    
    public function isSuccess(): string;
    
    public function getNextWebPage(): string;
}