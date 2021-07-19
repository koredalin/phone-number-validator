<?php

namespace App\Common;

use App\Common\Interfaces\ConfirmationCodeGeneratorInterface;

/**
 * Description of ConfirmationCode
 *
 * @author Hristo
 */
class ConfirmationCodeGenerator implements ConfirmationCodeGeneratorInterface
{
    const CONFIRMATION_CODE_MIN = 100000;
    const CONFIRMATION_CODE_MAX = 999999;
    
    public function generate(): int
    {
        return random_int(self::CONFIRMATION_CODE_MIN, self::CONFIRMATION_CODE_MAX);
    }
}
