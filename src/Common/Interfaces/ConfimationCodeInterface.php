<?php

namespace App\Common\Interfaces;

/**
 *
 * @author Hristo
 */
interface ConfimationCodeInterface
{
    public function generate(): int;
}
