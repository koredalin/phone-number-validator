<?php

// Reposities
use App\Users\UserRepository;
// Controllers
use App\Http\Controllers\GreetingsController;

return [
    GreetingsController::class => DI\create(GreetingsController::class)
        ->constructor(DI\get(CONTAINER_TWIG_ENVIRONMENT), DI\get(CONTAINER_RESPONSE), DI\get(UserRepository::class)),
];
