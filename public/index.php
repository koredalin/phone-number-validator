<?php

//ini_set('memory_limit', '1024M');

header('Access-Control-Allow-Headers: Access-Control-Allow-Origin, Content-Type');
header('Access-Control-Allow-Origin: http://localhost:4200', false);
header('Content-Type: application/json, charset=utf-8');

use Composer\Autoload\ClassLoader;
use Doctrine\Common\Annotations\AnnotationRegistry;

/**
 * @var ClassLoader $loader
 */
$loader = require_once __DIR__.'/../vendor/autoload.php';

/**
 * Loads not loaded Symfony Validator annotation files.
 */
AnnotationRegistry::registerLoader([$loader, 'loadClass']);

//require_once __DIR__.'/../config/db_cache_config.php';
require_once __DIR__.'/../config/config.php';

use DI\ContainerBuilder;
use App\Factory\RouteFactory;
use Laminas\HttpHandlerRunner\Emitter\SapiEmitter;


$containerBuilder = new ContainerBuilder();
$containerBuilder->addDefinitions(__DIR__.'/../config/di_config.php');
$container = $containerBuilder->build();

$route = RouteFactory::build($container);

$response = $route->dispatch($container->get(CONTAINER_REQUEST));
//echo __LINE__; exit;

$container->get(SapiEmitter::class)->emit($response);