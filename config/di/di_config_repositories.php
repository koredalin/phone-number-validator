<?php

// Doctrine libs
use Doctrine\ORM\EntityManagerInterface;
// Entities
use App\Entities\User;
use App\Entities\Country;
use App\Entities\Phone;
use App\Entities\Transaction;
use App\Entities\PhoneConfirmation;
use App\Entities\PhoneConfirmationAttempt;
// Repository Interfaces
use App\Repositories\Interfaces\UserRepositoryInterface;
use App\Repositories\Interfaces\CountryRepositoryInterface;
use App\Repositories\Interfaces\PhoneRepositoryInterface;
use App\Repositories\Interfaces\TransactionRepositoryInterface;
use App\Repositories\Interfaces\PhoneConfirmationRepositoryInterface;
use App\Repositories\Interfaces\PhoneConfirmationAttemptRepositoryInterface;
// Repositories
use App\Repositories\UserRepository;
use App\Repositories\CountryRepository;
use App\Repositories\PhoneRepository;
use App\Repositories\TransactionRepository;
use App\Repositories\PhoneConfirmationRepository;
use App\Repositories\PhoneConfirmationAttemptRepository;

return [
    UserRepositoryInterface::class => DI\create(UserRepository::class)
        ->constructor(DI\get(EntityManagerInterface::class), DI\get(User::class)),

    CountryRepositoryInterface::class => DI\create(CountryRepository::class)
        ->constructor(DI\get(EntityManagerInterface::class), DI\get(Country::class)),

    PhoneRepositoryInterface::class => DI\create(PhoneRepository::class)
        ->constructor(DI\get(EntityManagerInterface::class), DI\get(Phone::class)),

    TransactionRepositoryInterface::class => DI\create(TransactionRepository::class)
        ->constructor(DI\get(EntityManagerInterface::class), DI\get(Transaction::class)),

    PhoneConfirmationRepositoryInterface::class => DI\create(PhoneConfirmationRepository::class)
        ->constructor(DI\get(EntityManagerInterface::class), DI\get(PhoneConfirmation::class)),

    PhoneConfirmationAttemptRepositoryInterface::class => DI\create(PhoneConfirmationAttemptRepository::class)
        ->constructor(DI\get(EntityManagerInterface::class), DI\get(PhoneConfirmationAttempt::class)),
];
