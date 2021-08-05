<?php

require_once __DIR__.'/db_config.php';

use Doctrine\ORM\Tools\Setup;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityManager;

return [
    EntityManagerInterface::class => function () {
        $paths = [ENTITIES_DIRECTORY];
        $isDevMode = true;
        $config = Setup::createAnnotationMetadataConfiguration($paths, $isDevMode);
        return EntityManager::create(DB_CONNECTION_CONFIGURATION, $config);
    }
];