<?php

namespace App\Controllers\Input\Models;

use Symfony\Component\Validator\Constraints as Assert;

/**
 * Confirmation Code Form
 *
 * @author Hristo
 */
class ConfirmationCodeModel
{
    /**
     * @Assert\NotBlank
     * @Assert\Length(min=6)
     * @Assert\Length(max=10)
     * @Assert\Type(type="integer")
     */
    private int $confirmationCode;
    
    /**************************************************************************/
    /******************************* SETTERS **********************************/
    /**************************************************************************/
    
    public function setConfirmationCode($phoneNumber): void
    {
        $this->confirmationCode = $phoneNumber;
    }
    
    /**************************************************************************/
    /******************************* GETTERS **********************************/
    /**************************************************************************/
    
    public function getConfirmationCode(): int
    {
        return $this->confirmationCode;
    }
    
    /**************************************************************************/
    /**************************************************************************/
    /**************************************************************************/
}
