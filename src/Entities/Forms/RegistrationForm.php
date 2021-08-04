<?php

namespace App\Entities\Forms;

use Symfony\Component\Validator\Constraints as Assert;

//use Symfony\Component\Validator\Constraints\NotBlank;
//use Symfony\Component\Validator\Constraints\Email;

/**
 * Description of Registration
 *
 * @author Hristo
 */
class RegistrationForm
{
    /**
     * @Assert\NotBlank
     * @Assert\Email(
     *      message = "The email '{{ value }}' is not a valid email."
     * )
     */
    private $email;
    
    /**
     * @Assert\NotBlank
     * @Assert\Length(min=5)
     * @Assert\Length(max=20)
     * @Assert\Type(type="integer")
     */
    private $phoneNumber;
    
    /**
     * @Assert\NotBlank
     * @Assert\Length(min=3)
     * @Assert\Length(max=10)
     */
    private $password;
    
    /**************************************************************************/
    /******************************* SETTERS **********************************/
    /**************************************************************************/
    
    public function setEmail($email): void
    {
        $this->email = $email;
    }
    
    public function setPhoneNumber($phoneNumber): void
    {
        $this->phoneNumber = $phoneNumber;
    }
    
    public function setPassword($password): void
    {
        $this->password = $password;
    }
    
    /**************************************************************************/
    /******************************* GETTERS **********************************/
    /**************************************************************************/
    
    public function getEmail()
    {
        return $this->email;
    }
    
    public function getPhoneNumber()
    {
        return $this->phoneNumber;
    }
    
    public function getPassword()
    {
        return $this->password;
    }
    
    public function getRegistrationFormArr() {
        $result = [
            'email' => $this->email,
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
