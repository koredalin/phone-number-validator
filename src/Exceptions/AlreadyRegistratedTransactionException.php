<?php

namespace App\Exceptions;

// Response
use App\Controllers\ResponseStatuses as ResStatus;

/**
 * Description of AlreadyRegistratedTransactionException
 *
 * @author Hristo
 */
class AlreadyRegistratedTransactionException extends \Exception
{
    // Redefine the exception so message isn't optional
    public function __construct($message, $code = 0, \Throwable $previous = null)
    {
        $currentCode = $code > 0 ? $code : ResStatus::ALREADY_REPORTED;
        // make sure everything is assigned properly
        parent::__construct($message, $currentCode, $previous);
    }

    // custom string representation of object
    public function __toString() {
        return __CLASS__ . ": [{$this->code}]: {$this->message}\n";
    }
}
