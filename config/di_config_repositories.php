<?php

// Doctrine libs
use Doctrine\ORM\EntityManagerInterface;
// Repositories
//use App\Users\UserRepository;
use App\Repositories\Interfaces\UserRepositoryInterface;
use App\Repositories\UserRepository;
use App\Repositories\Services\UserService;

return [
//    UserRepository::class => DI\create(UserRepository::class)
//        ->constructor(DI\get(QueryBuilder::class)),
    UserRepositoryInterface::class => DI\create(UserRepository::class)
        ->constructor(DI\get(EntityManagerInterface::class)),
    UserService::class => DI\create(UserService::class)
        ->constructor(DI\get(UserRepositoryInterface::class)),
];
