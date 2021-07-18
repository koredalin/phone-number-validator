<?php

require_once './vendor/autoload.php';
require_once './config/db_config.php';

use Doctrine\DBAL\DriverManager;

return DriverManager::getConnection(DB_CONNECTION_CONFIGURATION);