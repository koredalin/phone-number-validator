<?php

// Doctrine libs
use Doctrine\DBAL\Query\QueryBuilder;

// Queries
use App\Queries\Interfaces\PhoneConfirmationAttemptQueryInterface;
use App\Queries\PhoneConfirmationAttemptQuery;
use App\Queries\Services\TransactionQueryService;

return [
    PhoneConfirmationAttemptQueryInterface::class => DI\create(PhoneConfirmationAttemptQuery::class)
        ->constructor(DI\get(QueryBuilder::class)),
    TransactionQueryService::class => DI\create(TransactionQueryService::class)
        ->constructor(DI\get(PhoneConfirmationAttemptQueryInterface::class)),
];
