<?php

namespace App\Services\Interfaces;

use App\Services\Interfaces\WebPageServiceInterface;
use App\Entities\Forms\RegistrationForm;
use App\Entities\PhoneConfirmation;

/**
 *
 * @author Hristo
 */
interface RegistrationServiceInterface extends WebPageServiceInterface
{
    public function createForm(string $requestBody): RegistrationForm;
    
    public function isValidForm(): bool;
    
    public function getFormErrors(): string;
    
    public function registrate(): ?PhoneConfirmation;
}
