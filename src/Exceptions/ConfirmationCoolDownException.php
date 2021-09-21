<?php

namespace App\Exceptions;

// Response
use App\Controllers\ResponseStatuses as ResStatus;
use App\Services\ConfirmationService;

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
        $instanceMessage = 'Minimum interval before next confirmation code attempt: '.ConfirmationService::COOL_DOWN_MINUTES.' minutes. ';
        $instanceCode = $code > 0 ? $code : ResStatus::FORBIDDEN;
        // make sure everything is assigned properly
        parent::__construct($instanceMessage, $instanceCode, $previous);
    }

    // custom string representation of object
    public function __toString() {
        return __CLASS__ . ": [{$this->code}]: {$this->message}\n";
    }
}
