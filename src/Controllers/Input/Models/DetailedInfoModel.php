<?php

namespace App\Controllers\Input\Models;

use Symfony\Component\Validator\Constraints as Assert;

/**
 * Detailed Info Form
 *
 * @author Hristo
 */
class DetailedInfoModel
{
    /**
     * @Assert\NotBlank
     * @Assert\Length(min=6)
     * @Assert\Length(max=10)
     * @Assert\Type(type="integer")
     */
    private string $password;
    
    /**************************************************************************/
    /******************************* SETTERS **********************************/
    /**************************************************************************/
    
    public function setPassword($phoneCode): void
    {
        $this->password = $phoneCode;
    }
    
    /**************************************************************************/
    /******************************* GETTERS **********************************/
    /**************************************************************************/
    
    public function getPassword(): string
    {
        return $this->password;
    }
    
    /**************************************************************************/
    /**************************************************************************/
    /**************************************************************************/
}
