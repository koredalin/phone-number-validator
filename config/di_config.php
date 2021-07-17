<?php

use Laminas\Diactoros\ServerRequestFactory;
use Laminas\Diactoros\Response;
use League\Route\Strategy\ApplicationStrategy;
use League\Route\Router;
use Laminas\HttpHandlerRunner\Emitter\SapiEmitter;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;
use Doctrine\DBAL\Query\QueryBuilder;
use Doctrine\DBAL\DriverManager;
use App\Common\Interfaces\DateTimeManagerInterface;
use App\Common\DateTimeManager;


define('CONTAINER_TWIG_ENVIRONMENT', 'Twig_Environment');
define('CONTAINER_REQUEST', 'request');
define('CONTAINER_RESPONSE', 'response');

$containerDeclarations = [
    // Setup request/responce
    CONTAINER_REQUEST => function () {
        return ServerRequestFactory::fromGlobals(
            $_SERVER, $_GET, $_POST, $_COOKIE, $_FILES
        );
    },
    
    CONTAINER_RESPONSE => DI\create(Response::class),
    
    ApplicationStrategy::class => DI\create(ApplicationStrategy::class),
    
    Router::class => DI\create(Router::class),
    
    SapiEmitter::class => DI\create(SapiEmitter::class),
    
    FilesystemLoader::class => DI\create(FilesystemLoader::class)
        ->constructor(__DIR__.'/../views'),
    
    CONTAINER_TWIG_ENVIRONMENT => DI\create(Environment::class)
        ->constructor(DI\get(
            FilesystemLoader::class),
            [
                'cache' => __DIR__.'/../views/cache',
                'debug' => true,
            ]),
    
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
    
    DateTimeManagerInterface::class => DI\create(DateTimeManager::class),
];
$containerDeclarations = array_merge($containerDeclarations, require_once __DIR__.DIRECTORY_SEPARATOR.'di_config_controllers.php');
$containerDeclarations = array_merge($containerDeclarations, require_once __DIR__.DIRECTORY_SEPARATOR.'di_config_repositories.php');

return $containerDeclarations;