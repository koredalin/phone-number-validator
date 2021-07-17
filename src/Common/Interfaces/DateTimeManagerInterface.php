<?php

namespace App\Common\Interfaces;

/**
 * Description of DateTimeManagerInterface
 *
 * @author Hristo
 */
interface DateTimeManagerInterface
{
    public function now(): \DateTime;
    public function nowStr(): string;
}
