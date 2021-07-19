<?php

// Doctrine libs
use Doctrine\ORM\EntityManagerInterface;
// Common
use App\Common\Interfaces\DateTimeManagerInterface;
use App\Common\Interfaces\PasswordGeneratorInterface;
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
// Repository Services
use App\Repositories\Services\TransactionRepositoryService;

return [
    TransactionRepositoryService::class => DI\create(TransactionRepositoryService::class)
        ->constructor(DI\get(TransactionRepositoryInterface::class), DI\get(PasswordGeneratorInterface::class), DI\get(DateTimeManagerInterface::class)),
];
