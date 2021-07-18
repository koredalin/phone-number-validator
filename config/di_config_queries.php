<?php

// Doctrine libs
use Doctrine\DBAL\Query\QueryBuilder;

// Queries
use App\Queries\Interfaces\TransactionQueryInterface;
use App\Queries\TransactionQuery;
use App\Queries\Services\TransactionQueryService;

return [
    TransactionQueryInterface::class => DI\create(TransactionQuery::class)
        ->constructor(DI\get(QueryBuilder::class)),
    TransactionQueryService::class => DI\create(TransactionQueryService::class)
        ->constructor(DI\get(TransactionQueryInterface::class)),
];
