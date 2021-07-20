<?php

namespace App\Entities\Forms;

/**
 * Description of Registration
 *
 * @author Hristo
 */
class Registration
{
    private string $email;
    private string $phoneNumber;
    private string $password;
    
    /**************************************************************************/
    /******************************* SETTERS **********************************/
    /**************************************************************************/
    
    public function setEmail(string $email): void
    {
        $this->email = $email;
    }
    
    public function setPhoneNumber(string $phoneNumber): void
    {
        $this->phoneNumber = $phoneNumber;
    }
    
    public function setPassword(string $password): void
    {
        $this->password = $password;
    }
    
    /**************************************************************************/
    /******************************* GETTERS **********************************/
    /**************************************************************************/
    
    public function getEmail(): string
    {
        return $this->email;
    }
    
    public function getPhoneNumber(): string
    {
        return $this->phoneNumber;
    }
    
    public function getPassword(): string
    {
        return $this->password;
    }
    
    /**************************************************************************/
    /**************************************************************************/
    /**************************************************************************/
}
