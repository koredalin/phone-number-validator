<?php

require_once './vendor/autoload.php';

require_once __DIR__.'/config/db_config.php';

use Doctrine\ORM\Tools\Setup;
use Doctrine\ORM\EntityManager;

$paths = [ENTITIES_DIRECTORY];
$isDevMode = false;
$proxyDir = DB_PROXY_DIR;
$config = Setup::createAnnotationMetadataConfiguration($paths, $isDevMode, $proxyDir);
$entityManager = EntityManager::create(DB_CONNECTION_CONFIGURATION, $config);
//$entityManager->getConnection()->getConfiguration()->setSQLLogger(null);