<?php

namespace App\Services\Interfaces;

use App\Entities\PhoneConfirmationAttempt;

/**
 *
 * @author Hristo
 */
interface ConfirmationServiceInterface
{
    public function confirmCode(int $transactionId): ?PhoneConfirmationAttempt;
    
    public function getDatabaseErrors(): string;
   
    public function getNextWebPage(): string;
}
