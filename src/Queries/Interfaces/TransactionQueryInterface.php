<?php

namespace App\Queries\Interfaces;

use App\Entities\Transaction;

/**
 *
 * @author Hristo
 */
interface TransactionQueryInterface
{
    public function all(): array;
}
