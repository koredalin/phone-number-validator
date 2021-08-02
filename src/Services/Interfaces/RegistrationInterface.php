<?php

namespace App\Services\Interfaces;

use App\Entities\Forms\RegistrationForm;
use Symfony\Component\Validator\ConstraintViolationList;
use App\Entities\PhoneConfirmationAttempt;

/**
 *
 * @author Hristo
 */
interface RegistrationInterface
{
    public function createForm(string $requestBody): RegistrationForm;
    
    public function isValidForm(): bool;
    
    public function getFormErrors(): ?ConstraintViolationList;
    
    public function registrate(): ?PhoneConfirmationAttempt;
    
    public function getDatabaseErrors(): string;
}
