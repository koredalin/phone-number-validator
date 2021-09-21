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
     * @Assert\Length(min=4)
     * @Assert\Length(max=20)
     * @Assert\Type(type="string")
     */
    private string $password;
    
    /**************************************************************************/
    /******************************* SETTERS **********************************/
    /**************************************************************************/
    
    public function setPassword(string $password): void
    {
        $this->password = $password;
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
