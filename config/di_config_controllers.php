<?php

// Reposities
use App\Users\UserRepository;
// Controllers
use App\Controllers\GreetingsController;
use App\Controllers\UserController;
use App\Repositories\Services\UserService;

return [
    GreetingsController::class => DI\create(GreetingsController::class)
        ->constructor(DI\get(CONTAINER_TWIG_ENVIRONMENT), DI\get(CONTAINER_RESPONSE), DI\get(UserRepository::class)),
    
    UserController::class => DI\create(UserController::class)
        ->constructor(DI\get(CONTAINER_TWIG_ENVIRONMENT), DI\get(CONTAINER_RESPONSE), DI\get(UserService::class)),
];
