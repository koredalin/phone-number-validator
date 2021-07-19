<?php

namespace App\Common\Interfaces;

/**
 *
 * @author Hristo
 */
interface ConfirmationCodeInterface
{
    public function generate(): int;
}
