<?php

define('ENTITIES_DIRECTORY', __DIR__.'/../src/Entities');

define('DB_CONNECTION_CONFIGURATION', [
    'host' => 'localhost:3306',
    'driver' => 'pdo_mysql',
    'dbname' => 'phone_validator',
    'user' => 'phone_validator',
    'password' => 'validator',
    'auto_generate_proxy_classes' => true,
]);