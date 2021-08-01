<?php

namespace App\Entities\Forms;

use Symfony\Component\Validator\Constraints as Assert;

/**
 * Description of Registration
 *
 * @author Hristo
 */
class Registration
{
    /**
     * @Assert\NotBlank
     * @Assert\Email
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
     * @Assert\Length(min=10)
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
    
    /**************************************************************************/
    /**************************************************************************/
    /**************************************************************************/
}
