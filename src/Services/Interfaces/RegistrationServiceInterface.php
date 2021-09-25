<?php

namespace App\Services\Interfaces;

use App\Services\Interfaces\WebPageServiceInterface;
// Input
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
    public function registratePhoneCodeNumber(RegistrationModelPhoneCodeNumber $form): PhoneConfirmation;
    
    public function registrateAssembledPhoneNumber(RegistrationModelAssembledPhoneNumber $form): PhoneConfirmation;
}
