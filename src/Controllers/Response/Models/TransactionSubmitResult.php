<?php

namespace App\Controllers\Response\Models;

/**
 * OTP TransactionSubmit
 *
 * @author Hristo
 */
class TransactionSubmitResult
{
    public ?bool $isSuccess;
    public ?int $transactionId;
    public string $email;
    public ?int $phoneCode;
    public string $phoneNumber;
    public string $transactionStatus;
    public string $error;
    public string $nextWebPage;
    public ?string $generatedConfirmationCode;
    
    private function __construct() {}
    
    public static function generateAnEmptyObject(): self
    {
        $obj = new self();
        $obj->isSuccess = null;
        $obj->transactionId = null;
        $obj->email = '';
        $obj->phoneCode = null;
        $obj->phoneNumber = '';
        $obj->transactionStatus = '';
        $obj->error = '';
        $obj->nextWebPage = '';
        $obj->generatedConfirmationCode = null;
        
        return $obj;
    }
}
