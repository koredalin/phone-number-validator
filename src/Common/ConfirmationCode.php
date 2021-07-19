<?php

namespace App\Common;

use App\Common\Interfaces\ConfirmationCodeInterface;

/**
 * Description of ConfirmationCode
 *
 * @author Hristo
 */
class ConfirmationCode implements ConfirmationCodeInterface
{
    const CONFIRMATION_CODE_MIN = 100000;
    const CONFIRMATION_CODE_MAX = 999999;
    
    public function generate(): int
    {
        return rand(self::CONFIRMATION_CODE_MIN, self::CONFIRMATION_CODE_MAX);
    }
}
