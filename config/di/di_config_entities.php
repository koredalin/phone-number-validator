<?php

use App\Entities\Transaction;
use App\Entities\User;
use App\Entities\Country;
use App\Entities\Phone;
use App\Entities\PhoneConfirmation;
use App\Entities\PhoneConfirmationAttempt;
use App\Controllers\Input\Models\RegistrationModelAssembledPhoneNumber;

return [
    Transaction::class => DI\create(),
    User::class => DI\create(),
    Country::class => DI\create(),
    Phone::class => DI\create(),
    PhoneConfirmation::class => DI\create(),
    PhoneConfirmationAttempt::class => DI\create(),
    RegistrationFormAssembledPhoneNumber::class => DI\create(),
];