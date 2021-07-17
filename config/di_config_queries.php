<?php

// Doctrine libs
use Doctrine\DBAL\Query\QueryBuilder;

// Queries
use App\Queries\Interfaces\UserQueryInterface;
use App\Queries\UserQuery;
use App\Queries\Services\UserQueryService;

return [
    UserQueryInterface::class => DI\create(UserQuery::class)
        ->constructor(DI\get(QueryBuilder::class)),
    UserQueryService::class => DI\create(UserQueryService::class)
        ->constructor(DI\get(UserQueryInterface::class)),
];
