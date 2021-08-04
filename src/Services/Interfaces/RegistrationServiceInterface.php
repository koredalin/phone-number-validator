<?php

namespace App\Services\Interfaces;

use App\Entities\Forms\RegistrationForm;
use App\Entities\PhoneConfirmation;

/**
 *
 * @author Hristo
 */
interface RegistrationServiceInterface
{
    public function createForm(string $requestBody): RegistrationForm;
    
    public function isValidForm(): bool;
    
    public function getFormErrors(): string;
    
    public function registrate(): ?PhoneConfirmation;
    
    public function getDatabaseErrors(): string;
   
    public function getNextWebPage(): string;
}
