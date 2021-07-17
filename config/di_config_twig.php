<?php

use Twig\Loader\FilesystemLoader;
use Twig\Environment;

define('CONTAINER_TWIG_FILESYSTEM_LOADER', 'container_twig_filesystem_loader');
define('CONTAINER_TWIG_ENVIRONMENT', 'twig_environment');

return [
    CONTAINER_TWIG_FILESYSTEM_LOADER => DI\create(FilesystemLoader::class)
        ->constructor(__DIR__.'/../views'),
    
    CONTAINER_TWIG_ENVIRONMENT => DI\create(Environment::class)
        ->constructor(DI\get(
            CONTAINER_TWIG_FILESYSTEM_LOADER),
            [
                'cache' => __DIR__.'/../views/cache',
                'debug' => true,
            ]
        ),
];