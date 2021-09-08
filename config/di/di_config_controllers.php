<?php

// Reposities
use App\Users\UserRepository;
// Controllers
use App\Controllers\GreetingsController;
use App\Controllers\UserController;
use App\Controllers\RegistrationController;
use App\Controllers\ConfirmationController;
use App\Controllers\TransactionInfoController;
// Query Services
use App\Queries\Services\TransactionQueryService;
// General Services
use App\Services\Interfaces\RegistrationServiceInterface;
use App\Services\Interfaces\ConfirmationServiceInterface;
use App\Services\Interfaces\ResetServiceInterface;
use App\Services\Interfaces\TransactionRepositoryServiceInterface;
use App\Controllers\Response\Interfaces\ResponseAssembleInterface;

return [
    GreetingsController::class => DI\create(GreetingsController::class)
        ->constructor(DI\get(CONTAINER_TWIG_ENVIRONMENT), DI\get(CONTAINER_RESPONSE), DI\get(UserRepository::class)),
    
    UserController::class => DI\create(UserController::class)
        ->constructor(DI\get(CONTAINER_TWIG_ENVIRONMENT), DI\get(CONTAINER_RESPONSE), DI\get(TransactionRepositoryService::class), DI\get(TransactionQueryService::class)),

    RegistrationController::class => DI\create(RegistrationController::class)
        ->constructor(DI\get(CONTAINER_RESPONSE), DI\get(RegistrationServiceInterface::class), DI\get(ResponseAssembleInterface::class)),
    
    ConfirmationController::class => DI\create(ConfirmationController::class)
        ->constructor(DI\get(CONTAINER_RESPONSE), DI\get(ConfirmationServiceInterface::class), DI\get(ResetServiceInterface::class), DI\get(ResponseAssembleInterface::class)),
    
    TransactionInfoController::class => DI\create(TransactionInfoController::class)
        ->constructor(DI\get(CONTAINER_RESPONSE), DI\get(TransactionRepositoryServiceInterface::class), DI\get(ResponseAssembleInterface::class)),
];
