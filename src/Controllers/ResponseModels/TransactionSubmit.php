<?php

namespace App\Controllers\ResponseModels;

/**
 * OTP TransactionSubmit
 *
 * @author Hristo
 */
class TransactionSubmit
{
    public string $email;
    public ?int $phoneCode;
    public string $phoneNumber;
    public ?int $transactionId;
    public string $transactionStatus;
    public string $nextWebPage;
    public ?bool $isSuccess;
    public string $error;
    
    private function __construct() {}
    
    public static function generateAnEmptyObject(): self
    {
        $obj = new self();
        $obj->email = '';
        $obj->phoneCode = null;
        $obj->phoneNumber = '';
        $obj->transactionId = null;
        $obj->transactionStatus = '';
        $obj->nextWebPage = '';
        $obj->isSuccess = null;
        $obj->error = '';
        
        return $obj;
    }
}
