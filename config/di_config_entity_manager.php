<?php

require_once __DIR__.'/db_config.php';

use Doctrine\ORM\Tools\Setup;
use Doctrine\ORM\EntityManager;

return [
    EntityManager::class => function () {
        $paths = [ENTITIES_DIRECTORY];
        $isDevMode = false;
        $config = Setup::createAnnotationMetadataConfiguration($paths, $isDevMode);
        return EntityManager::create(DB_CONNECTION_CONFIGURATION, $config);
    }
];