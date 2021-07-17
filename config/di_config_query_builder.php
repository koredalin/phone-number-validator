<?php

use Doctrine\DBAL\Query\QueryBuilder;
use Doctrine\DBAL\DriverManager;

return [
    QueryBuilder::class => function () {
        $options = [
            'dbname' => 'phone_validator',
            'user' => 'phone_validator',
            'password' => 'validator',
            'host' => 'localhost:3306',
            'driver' => 'pdo_mysql',
        ];
        $connection = DriverManager::getConnection($options);
        
        return $connection->createQueryBuilder();
    },
];

