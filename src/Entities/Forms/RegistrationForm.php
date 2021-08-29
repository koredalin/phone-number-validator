<?php

namespace App\Entities\Forms;

use Symfony\Component\Validator\Constraints as Assert;

//use Symfony\Component\Validator\Constraints\NotBlank;
//use Symfony\Component\Validator\Constraints\Email;

/**
 * Registration Form
 *
 * @author Hristo
 */
abstract class RegistrationForm
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
    
    public function getPassword()
    {
        return $this->password;
    }
    
    /**************************************************************************/
    /**************************************************************************/
    /**************************************************************************/
}
