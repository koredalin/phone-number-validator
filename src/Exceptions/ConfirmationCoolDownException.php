<?php

namespace App\Exceptions;

// Response
use App\Controllers\ResponseStatuses as ResStatus;

/**
 * Class ConfirmationCoolDownException
 * If the user input is not valid.
 * Standard response status code should be: 422.
 *
 * @author Hristo
 */
class ConfirmationCoolDownException extends \Exception
{
    // Redefine the exception so message isn't optional
    public function __construct($message, $code = 0, \Throwable $previous = null)
    {
        $currentCode = $code > 0 ? $code : ResStatus::FORBIDDEN;
        // make sure everything is assigned properly
        parent::__construct($message, $currentCode, $previous);
    }

    // custom string representation of object
    public function __toString() {
        return __CLASS__ . ": [{$this->code}]: {$this->message}\n";
    }
}
