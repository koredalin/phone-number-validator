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
// Repository Services
use App\Services\UserRepositoryService;
use App\Services\CountryRepositoryService;
use App\Services\PhoneRepositoryService;
use App\Services\TransactionRepositoryService;
use App\Services\PhoneConfirmationRepositoryService;
use App\Services\PhoneConfirmationAttemptRepositoryService;

use App\Services\Interfaces\TransactionRepositoryServiceInterface;

return [
    UserRepositoryService::class => DI\create(UserRepositoryService::class)
        ->constructor(DI\get(UserRepositoryInterface::class), DI\get(DateTimeManagerInterface::class)),

    CountryRepositoryService::class => DI\create(CountryRepositoryService::class)
        ->constructor(DI\get(CountryRepositoryInterface::class), DI\get(DateTimeManagerInterface::class)),

    PhoneRepositoryService::class => DI\create(PhoneRepositoryService::class)
        ->constructor(DI\get(PhoneRepositoryInterface::class), DI\get(CountryRepositoryInterface::class), DI\get(DateTimeManagerInterface::class)),

    TransactionRepositoryService::class => DI\create(TransactionRepositoryService::class)
        ->constructor(DI\get(TransactionRepositoryInterface::class), DI\get(PasswordGeneratorInterface::class), DI\get(DateTimeManagerInterface::class)),

    PhoneConfirmationRepositoryService::class => DI\create(PhoneConfirmationRepositoryService::class)
        ->constructor(DI\get(PhoneConfirmationRepositoryInterface::class), DI\get(ConfirmationCodeGeneratorInterface::class), DI\get(DateTimeManagerInterface::class)),

    PhoneConfirmationAttemptRepositoryService::class => DI\create(PhoneConfirmationAttemptRepositoryService::class)
        ->constructor(DI\get(PhoneConfirmationAttemptRepositoryInterface::class), DI\get(DateTimeManagerInterface::class)),
];
