<?php

namespace App\Services\Interfaces;

use App\Services\Interfaces\WebPageServiceInterface;
use App\Entities\Forms\RegistrationFormAssembledPhoneNumber;
use App\Entities\PhoneConfirmation;

/**
 *
 * @author Hristo
 */
interface RegistrationServiceInterface extends WebPageServiceInterface
{
    public function createForm(string $requestBody): RegistrationFormAssembledPhoneNumber;
    
    public function isValidForm(): bool;
    
    public function getFormErrors(): string;
    
    public function registrate(): ?PhoneConfirmation;
}
