<?php

namespace App\Controllers\Input\Models;

use Symfony\Component\Validator\Constraints as Assert;
use App\Controllers\Input\Models\RegistrationModel;

/**
 * Registration Form with phone code and phone number
 *
 * @author Hristo
 */
class RegistrationModelPhoneCodeNumber extends RegistrationModel
{
    /**
     * @Assert\NotBlank
     * @Assert\Length(max=6)
     * @Assert\Type(type="integer")
     */
    private $phoneCode;
    
    /**
     * @Assert\NotBlank
     * @Assert\Length(min=5)
     * @Assert\Length(max=20)
     * @Assert\Type(type="integer")
     */
    private $phoneNumber;
    
    /**************************************************************************/
    /******************************* SETTERS **********************************/
    /**************************************************************************/
    
    public function setPhoneCode($phoneCode): void
    {
        $this->phoneCode = $phoneCode;
    }
    
    public function setPhoneNumber($phoneNumber): void
    {
        $this->phoneNumber = $phoneNumber;
    }
    
    /**************************************************************************/
    /******************************* GETTERS **********************************/
    /**************************************************************************/
    
    public function getPhoneCode()
    {
        return $this->phoneCode;
    }
    
    public function getPhoneNumber()
    {
        return $this->phoneNumber;
    }
    
    public function getRegistrationFormArr() {
        $result = [
            'email' => $this->email,
            'phoneNumber' => $this->phoneCode,
            'phoneNumber' => $this->phoneNumber,
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
