<?php

use App\Entities\User;
use App\Entities\Email;
use App\Entities\Country;
use App\Entities\Phone;
use App\Entities\PhoneConfirmation;
use App\Entities\PhoneConfirmationAttempt;

return [
    User::class => DI\create(),
    Email::class => DI\create(),
    Country::class => DI\create(),
    Phone::class => DI\create(),
    PhoneConfirmation::class => DI\create(),
    PhoneConfirmationAttempt::class => DI\create(),
];