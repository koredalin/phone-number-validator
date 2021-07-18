<?php

namespace App\Common\Interfaces;

/**
 *
 * @author Hristo
 */
interface PasswordGeneratorInterface
{
    public function encode(): string;
}
