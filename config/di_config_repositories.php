<?php

// Doctrine libs
use Doctrine\ORM\EntityManagerInterface;
// Repositories
use App\Repositories\Interfaces\UserRepositoryInterface;
use App\Repositories\UserRepository;
use App\Repositories\Services\UserRepositoryService;
use App\Common\Interfaces\DateTimeManagerInterface;
use App\Entities\User;

return [
    UserRepositoryInterface::class => DI\create(UserRepository::class)
        ->constructor(DI\get(EntityManagerInterface::class), DI\get(User::class)),
    UserRepositoryService::class => DI\create(UserRepositoryService::class)
        ->constructor(DI\get(UserRepositoryInterface::class), DI\get(DateTimeManagerInterface::class)),
];
