<?php

use Laminas\Diactoros\ServerRequestFactory;
use Laminas\Diactoros\Response;
use League\Route\Strategy\ApplicationStrategy;
use League\Route\Strategy\JsonStrategy;
use League\Route\Router;
use Laminas\HttpHandlerRunner\Emitter\SapiEmitter;

use Psr\Http\Message\ResponseFactoryInterface;
use Laminas\Diactoros\ResponseFactory;

define('CONTAINER_REQUEST', 'request');
define('CONTAINER_RESPONSE', 'response');

return [
    // Setup request/responce
    CONTAINER_REQUEST => function () {
        return ServerRequestFactory::fromGlobals(
            $_SERVER, $_GET, $_POST, $_COOKIE, $_FILES
        );
    },
    
    CONTAINER_RESPONSE => DI\create(Response::class),
        
    ApplicationStrategy::class => DI\create(ApplicationStrategy::class),
    ResponseFactoryInterface::class => DI\create(ResponseFactory::class),
    JsonStrategy::class => DI\create(JsonStrategy::class)
            ->constructor(DI\get(ResponseFactory::class)),
    
    Router::class => DI\create(Router::class),
    
    SapiEmitter::class => DI\create(SapiEmitter::class),
];