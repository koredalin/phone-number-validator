<?php

define('ENTITIES_DIRECTORY', __DIR__.'/../src/Entities');
define('DB_PROXY_DIR', __DIR__.'/db_proxy_dir');

define('DB_CONNECTION_CONFIGURATION', [
    'host' => 'localhost:3306',
    'driver' => 'pdo_mysql',
    'dbname' => 'phone_validator_tests',
    'user' => 'phone_validator_tests',
    'password' => 'pv_tests',
    'auto_generate_proxy_classes' => true,
]);