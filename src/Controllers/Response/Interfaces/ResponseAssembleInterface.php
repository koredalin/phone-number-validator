<?php

namespace App\Controllers\Response\Interfaces;

use App\Controllers\Response\Models\TransactionSubmit as TransactionResponse;

/**
 *
 * @author Hristo
 */
interface ResponseAssembleInterface
{
    public function assembleResponse(): TransactionResponse;
    public function resetResponse(): void;
}
