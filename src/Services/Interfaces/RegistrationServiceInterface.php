<?php

namespace App\Services\Interfaces;

use App\Services\Interfaces\WebPageServiceInterface;
use App\Controllers\Input\Models\RegistrationModelPhoneCodeNumber;
use App\Controllers\Input\Models\RegistrationModelAssembledPhoneNumber;
use App\Entities\PhoneConfirmation;

/**
 *
 * @author Hristo
 */
interface RegistrationServiceInterface extends WebPageServiceInterface
{
    public function createFormFromPhoneCodeNumber(string $requestBody): RegistrationModelPhoneCodeNumber;
    
    public function createFormFromAssembledPhoneNumber(string $requestBody): RegistrationModelAssembledPhoneNumber;
    
    public function isValidForm(): bool;
    
    public function getFormErrors(): string;
    
    public function registrate(): PhoneConfirmation;
}
