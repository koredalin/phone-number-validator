<?php

// Doctrine libs
use Doctrine\ORM\EntityManagerInterface;
// Repositories
use App\Repositories\Interfaces\TransactionRepositoryInterface;
use App\Repositories\TransactionRepository;
use App\Repositories\Services\TransactionRepositoryService;
use App\Common\Interfaces\DateTimeManagerInterface;
use App\Entities\Transaction;

return [
    TransactionRepositoryInterface::class => DI\create(TransactionRepository::class)
        ->constructor(DI\get(EntityManagerInterface::class), DI\get(Transaction::class)),
    TransactionRepositoryService::class => DI\create(TransactionRepositoryService::class)
        ->constructor(DI\get(TransactionRepositoryInterface::class), DI\get(DateTimeManagerInterface::class)),
];
