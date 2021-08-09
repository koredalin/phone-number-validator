<?php

namespace App\Services\Interfaces;

use App\Entities\Transaction;

/**
 *
 * @author Hristo
 */
interface SuccessSmsInterface
{
    public function sendSuccessMessage(int $transactionId): Transaction;
}
