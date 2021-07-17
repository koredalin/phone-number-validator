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

$container->get(SapiEmitter::class)->emit($response);

?>


<!--<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>Phone Validator</title>
    </head>
    <body>
        
        <h1>Phone Validator</h1>
    </body>
</html>-->
