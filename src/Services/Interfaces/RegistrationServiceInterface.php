<?php

namespace App\Services\Interfaces;

use App\Services\Interfaces\WebPageServiceInterface;
// Input
use App\Controllers\Input\Models\RegistrationModel;
use App\Controllers\Input\Models\RegistrationModelPhoneCodeNumber;
use App\Controllers\Input\Models\RegistrationModelAssembledPhoneNumber;
// Entities
use App\Entities\PhoneConfirmation;

/**
 *
 * @author Hristo
 */
interface RegistrationServiceInterface extends WebPageServiceInterface
{
//    public function createFormFromPhoneCodeNumber(string $requestBody): RegistrationModelPhoneCodeNumber;
    
//    public function createFormFromAssembledPhoneNumber(string $requestBody): RegistrationModelAssembledPhoneNumber;
    
//    public function isValidForm(): bool;
    
//    public function getFormErrors(): string;
    
//    public function registrate(RegistrationModel $form): PhoneConfirmation;
    
    public function registratePhoneCodeNumber(RegistrationModelPhoneCodeNumber $form): PhoneConfirmation;
    
    public function registrateAssembledPhoneNumber(RegistrationModelAssembledPhoneNumber $form): PhoneConfirmation;
}
