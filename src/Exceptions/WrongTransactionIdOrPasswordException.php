<?php

namespace App\Exceptions;

// Response
use App\Controllers\ResponseStatuses as ResStatus;

/**
 * Class WrongTransactionIdPasswordException
 * Not valid transaction id, password couple.
 *
 * @author Hristo
 */
class WrongTransactionIdOrPasswordException extends \Exception
{
    // Redefine the exception so message isn't optional
    public function __construct($message, $code = 0, \Throwable $previous = null)
    {
        $instanceMessage = 'Wrong transaction id or password.';
        $instanceCode = $code > 0 ? $code : ResStatus::FORBIDDEN;
        // make sure everything is assigned properly
        parent::__construct($instanceMessage, $instanceCode, $previous);
    }

    // custom string representation of object
    public function __toString() {
        return __CLASS__ . ": [{$this->code}]: {$this->message}\n";
    }
}
