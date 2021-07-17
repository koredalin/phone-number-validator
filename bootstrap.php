<?php

require_once __DIR__.'/config/db_config.php';

use Doctrine\ORM\Tools\Setup;
use Doctrine\ORM\EntityManager;

$paths = [ENTITIES_DIRECTORY];
$isDevMode = false;

$config = Setup::createAnnotationMetadataConfiguration($paths, $isDevMode);
$entityManager = EntityManager::create(DB_CONNECTION_CONFIGURATION, $config);