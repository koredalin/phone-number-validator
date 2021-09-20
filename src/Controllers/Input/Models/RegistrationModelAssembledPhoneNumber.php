<?php

namespace App\Controllers\Input\Models;

use Symfony\Component\Validator\Constraints as Assert;
use App\Controllers\Input\Models\RegistrationModel;

/**
 * Registration Form with assembled phone code and phone number
 *
 * @author Hristo
 */
class RegistrationModelAssembledPhoneNumber extends RegistrationModel
{
    /**
     * @Assert\NotBlank
     * @Assert\Length(min=5)
     * @Assert\Length(max=20)
     * @Assert\Type(type="integer")
     */
    private $assembledPhoneNumber;
    
    /**************************************************************************/
    /******************************* SETTERS **********************************/
    /**************************************************************************/
    
    public function setAssembledPhoneNumber($assembledPhoneNumber): void
    {
        $this->assembledPhoneNumber = $assembledPhoneNumber;
    }
    
    /**************************************************************************/
    /******************************* GETTERS **********************************/
    /**************************************************************************/
    
    public function getAssembledPhoneNumber()
    {
        return $this->assembledPhoneNumber;
    }
    
    public function getRegistrationFormArr() {
        $result = [
            'email' => $this->email,
            'phoneNumber' => $this->assembledPhoneNumber,
            'password' => $this->password,
        ];
        
        return $result;
    }
    
    public function getRegistrationFormJson() {
        return \json_encode($this->getRegistrationFormArr());
    }
    
    /**************************************************************************/
    /**************************************************************************/
    /**************************************************************************/
}
