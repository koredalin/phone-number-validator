<?php

// Reposities
use App\Users\UserRepository;
// Controllers
use App\Controllers\GreetingsController;
use App\Controllers\UserController;
use App\Controllers\ConfirmationController;
use App\Controllers\RegistrationController;
// Query Services
use App\Queries\Services\TransactionQueryService;
// General Services
use App\Services\Interfaces\RegistrationServiceInterface;
use App\Services\Interfaces\ConfirmationServiceInterface;

return [
    GreetingsController::class => DI\create(GreetingsController::class)
        ->constructor(DI\get(CONTAINER_TWIG_ENVIRONMENT), DI\get(CONTAINER_RESPONSE), DI\get(UserRepository::class)),
    
    UserController::class => DI\create(UserController::class)
        ->constructor(DI\get(CONTAINER_TWIG_ENVIRONMENT), DI\get(CONTAINER_RESPONSE), DI\get(TransactionRepositoryService::class), DI\get(TransactionQueryService::class)),

    RegistrationController::class => DI\create(RegistrationController::class)
        ->constructor(DI\get(CONTAINER_RESPONSE), DI\get(RegistrationServiceInterface::class)),
    
    ConfirmationController::class => DI\create(ConfirmationController::class)
        ->constructor(DI\get(CONTAINER_RESPONSE), DI\get(ConfirmationServiceInterface::class)),
];
