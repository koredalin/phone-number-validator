<?php

require_once __DIR__.'/../vendor/autoload.php';

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