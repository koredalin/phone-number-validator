<?php

namespace App\Queries\Interfaces;

use App\Entities\Transaction;

/**
 *
 * @author Hristo
 */
interface PhoneConfirmationAttemptQueryInterface
{
    public function all(): array;
}
