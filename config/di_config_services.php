<?php

// Common
use App\Common\Interfaces\DateTimeManagerInterface;
use App\Common\Interfaces\PasswordGeneratorInterface;
use App\Common\Interfaces\ConfirmationCodeGeneratorInterface;
// Repository Interfaces
use App\Repositories\Interfaces\UserRepositoryInterface;
use App\Repositories\Interfaces\CountryRepositoryInterface;
use App\Repositories\Interfaces\PhoneRepositoryInterface;
use App\Repositories\Interfaces\TransactionRepositoryInterface;
use App\Repositories\Interfaces\PhoneConfirmationRepositoryInterface;
use App\Repositories\Interfaces\PhoneConfirmationAttemptRepositoryInterface;

// Repository service interfaces
use App\Services\Interfaces\CountryRepositoryServiceInterface;
use App\Services\Interfaces\UserRepositoryServiceInterface;
use App\Services\Interfaces\PhoneRepositoryServiceInterface;
use App\Services\Interfaces\TransactionRepositoryServiceInterface;
use App\Services\Interfaces\PhoneConfirmationRepositoryServiceInterface;
use App\Services\Interfaces\PhoneConfirmationAttemptRepositoryServiceInterface;
use App\Services\Interfaces\RegistrationServiceInterface;
use App\Services\Interfaces\ConfirmationServiceInterface;
// Repository Services
use App\Services\UserRepositoryService;
use App\Services\CountryRepositoryService;
use App\Services\PhoneRepositoryService;
use App\Services\TransactionRepositoryService;
use App\Services\PhoneConfirmationRepositoryService;
use App\Services\PhoneConfirmationAttemptRepositoryService;
use App\Services\RegistrationService;
use App\Services\ConfirmationService;

use App\Entities\Forms\RegistrationForm;


return [
    CountryRepositoryServiceInterface::class => DI\create(CountryRepositoryService::class)
        ->constructor(DI\get(CountryRepositoryInterface::class), DI\get(DateTimeManagerInterface::class)),

    UserRepositoryServiceInterface::class => DI\create(UserRepositoryService::class)
        ->constructor(DI\get(UserRepositoryInterface::class), DI\get(DateTimeManagerInterface::class)),

    PhoneRepositoryServiceInterface::class => DI\create(PhoneRepositoryService::class)
        ->constructor(DI\get(PhoneRepositoryInterface::class), DI\get(CountryRepositoryInterface::class), DI\get(DateTimeManagerInterface::class)),

    TransactionRepositoryServiceInterface::class => DI\create(TransactionRepositoryService::class)
        ->constructor(DI\get(TransactionRepositoryInterface::class), DI\get(PasswordGeneratorInterface::class), DI\get(DateTimeManagerInterface::class)),

    PhoneConfirmationRepositoryServiceInterface::class => DI\create(PhoneConfirmationRepositoryService::class)
        ->constructor(DI\get(PhoneConfirmationRepositoryInterface::class), DI\get(ConfirmationCodeGeneratorInterface::class), DI\get(DateTimeManagerInterface::class)),

    PhoneConfirmationAttemptRepositoryServiceInterface::class => DI\create(PhoneConfirmationAttemptRepositoryService::class)
        ->constructor(DI\get(PhoneConfirmationAttemptRepositoryInterface::class), DI\get(DateTimeManagerInterface::class)),

    RegistrationServiceInterface::class => DI\create(RegistrationService::class)
        ->constructor(
            DI\get(RegistrationForm::class),
            DI\get(UserRepositoryServiceInterface::class),
            DI\get(PhoneRepositoryServiceInterface::class),
            DI\get(TransactionRepositoryServiceInterface::class),
            DI\get(PhoneConfirmationRepositoryServiceInterface::class)
        ),

    ConfirmationServiceInterface::class => DI\create(ConfirmationService::class)
        ->constructor(
            DI\get(TransactionRepositoryInterface::class),
            DI\get(PhoneConfirmationRepositoryInterface::class),
            DI\get(PhoneConfirmationAttemptRepositoryServiceInterface::class),
            DI\get(DateTimeManagerInterface::class)
        ),
];