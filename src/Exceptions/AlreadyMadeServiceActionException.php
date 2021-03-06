<?php

namespace App\Exceptions;

// Response
use App\Controllers\ResponseStatuses as ResStatus;

/**
 * Description of AlreadyMadeServiceActionException
 *
 * @author Hristo
 */
class AlreadyMadeServiceActionException extends \Exception
{
    // Redefine the exception so message isn't optional
    public function __construct($message, $code = 0, \Throwable $previous = null)
    {
        $instanceMessage = 'Already made service action type: '.trim($message).'.';
        $instanceCode = $code > 0 ? $code : ResStatus::INTERNAL_SERVER_ERROR;
        // make sure everything is assigned properly
        parent::__construct($instanceMessage, $instanceCode, $previous);
    }

    // custom string representation of object
    public function __toString() {
        return __CLASS__ . ": [{$this->code}]: {$this->message}\n";
    }
}
