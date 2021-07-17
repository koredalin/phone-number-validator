<?php

namespace App\Common\Interfaces;

/**
 *
 * @author Hristo
 */
interface DbInstanceInterface
{
    public function generate(): \PDO;
}
