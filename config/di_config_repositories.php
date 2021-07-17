<?php

// Doctrine libs
use Doctrine\DBAL\Query\QueryBuilder;
// Repositories
use App\Users\UserRepository;

return [
    UserRepository::class => DI\create(UserRepository::class)
        ->constructor(DI\get(QueryBuilder::class)),
];
