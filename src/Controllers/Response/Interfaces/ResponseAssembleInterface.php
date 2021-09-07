<?php

namespace App\Controllers\Response\Interfaces;

use App\Controllers\Response\Models\TransactionSubmit as TransactionResponse;
use App\Entities\Transaction;

/**
 *
 * @author Hristo
 */
interface ResponseAssembleInterface
{
    public function assembleResponse(
        ?Transaction $transaction,
        ?bool $isSuccess = null,
        string $error = '',
        bool $isRestrictedInfo = true,
        string $nextWebPage = ''
    ): TransactionResponse;
    
    public function resetResponse(): void;
}
