<?php

namespace App\Controllers\Response\Models;

use \DateTime;

/**
 * OTP TransactionSubmit
 *
 * @author Hristo
 */
class TransactionSubmitResult
{
    public ?int $transactionId;
    public string $email;
    public ?int $phoneCode;
    public string $phoneNumber;
    public string $transactionStatus;
    public ?DateTime $transactionConfirmedAt;
    public string $error;
    public string $nextWebPage;
    public ?string $generatedConfirmationCode;
    
    private function __construct() {}
    
    public static function generateAnEmptyObject(): self
    {
        $obj = new self();
        $obj->transactionId = null;
        $obj->email = '';
        $obj->phoneCode = null;
        $obj->phoneNumber = '';
        $obj->transactionStatus = '';
        $obj->transactionConfirmedAt = null;
        $obj->error = '';
        $obj->nextWebPage = '';
        $obj->generatedConfirmationCode = null;
        
        return $obj;
    }
}
