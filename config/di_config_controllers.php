<?php

// Reposities
use App\Users\UserRepository;
// Controllers
use App\Controllers\GreetingsController;
use App\Controllers\UserController;
use App\Controllers\PhoneConfirmationController;
// Repository Services
use App\Repositories\Services\UserRepositoryService;
use App\Repositories\Services\PhoneRepositoryService;
use App\Repositories\Services\TransactionRepositoryService;
use App\Repositories\Services\PhoneConfirmationRepositoryService;
// Query Services
use App\Queries\Services\TransactionQueryService;

return [
    GreetingsController::class => DI\create(GreetingsController::class)
        ->constructor(DI\get(CONTAINER_TWIG_ENVIRONMENT), DI\get(CONTAINER_RESPONSE), DI\get(UserRepository::class)),
    
    UserController::class => DI\create(UserController::class)
        ->constructor(DI\get(CONTAINER_TWIG_ENVIRONMENT), DI\get(CONTAINER_RESPONSE), DI\get(TransactionRepositoryService::class), DI\get(TransactionQueryService::class)),
    
    PhoneConfirmationController::class => DI\create(PhoneConfirmationController::class)
        ->constructor(DI\get(CONTAINER_TWIG_ENVIRONMENT), DI\get(CONTAINER_RESPONSE), DI\get(UserRepositoryService::class), DI\get(PhoneRepositoryService::class), DI\get(TransactionRepositoryService::class), DI\get(PhoneConfirmationRepositoryService::class)),
];
