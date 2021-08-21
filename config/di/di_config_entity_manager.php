<?php

// Enable official database
require_once __DIR__.'/../db/db_config.php';
// Enable test database
//require_once __DIR__.'/../db/db_config_tests.php';

use Doctrine\ORM\Tools\Setup;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityManager;

return [
    EntityManagerInterface::class => function () {
        $paths = [ENTITIES_DIRECTORY];
        $isDevMode = false;
        $proxyDir = DB_PROXY_DIR;
        $config = Setup::createAnnotationMetadataConfiguration($paths, $isDevMode, $proxyDir);
        $config->setAutoGenerateProxyClasses(true);
//        $config->setSecondLevelCacheEnabled(false);
        $em = EntityManager::create(DB_CONNECTION_CONFIGURATION, $config);
        $em->getConnection()->getConfiguration()->setSQLLogger(null);
        
        return $em;
    }
];