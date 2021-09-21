<?php

namespace App\Exceptions;

// Response
use App\Controllers\ResponseStatuses as ResStatus;
use App\Services\ResetService;

/**
 * Class ConfirmationResetCoolDownException
 * If the user input is not valid.
 * Standard response status code should be: 422.
 *
 * @author Hristo
 */
class ConfirmationResetCoolDownException extends \Exception
{
    // Redefine the exception so message isn't optional
    public function __construct($message, $code = 0, \Throwable $previous = null)
    {
        $instanceMessage = 'Minimum interval before confirmation code reset - '.ResetService::MINUTES_BEFORE_RESET_START.' minutes.';
        $instanceCode = $code > 0 ? $code : ResStatus::FORBIDDEN;
        // make sure everything is assigned properly
        parent::__construct($instanceMessage, $instanceCode, $previous);
    }

    // custom string representation of object
    public function __toString() {
        return __CLASS__ . ": [{$this->code}]: {$this->message}\n";
    }
}
