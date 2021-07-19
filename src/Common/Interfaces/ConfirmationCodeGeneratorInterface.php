<?php

namespace App\Common\Interfaces;

/**
 *
 * @author Hristo
 */
interface ConfirmationCodeGeneratorInterface
{
    public function generate(): int;
}
