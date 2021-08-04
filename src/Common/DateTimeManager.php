<?php

namespace App\Common;

use App\Common\Interfaces\DateTimeManagerInterface;

/**
 * Description of DateTimeManager
 *
 * @author Hristo
 */
class DateTimeManager implements DateTimeManagerInterface
{
    public function now(): \DateTime
    {
        return new \DateTime('NOW');
    }
    
    public function nowStr(): string
    {
        return $this->now()::format('Y-m-d H:i:s');
    }
}
