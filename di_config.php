<?php

use Laminas\Diactoros\ServerRequestFactory;
use Laminas\Diactoros\Response;
use Laminas\Diactoros\ResponseFactory;
use League\Route\Strategy\JsonStrategy;
use League\Route\Router;
use Laminas\HttpHandlerRunner\Emitter\SapiEmitter;

return [
    // Setup request/responce
    'request' => function () {
        return ServerRequestFactory::fromGlobals(
            $_SERVER, $_GET, $_POST, $_COOKIE, $_FILES
        );
    },
            
    'response' => DI\create(Response::class),
        
    ResponseFactory::class => DI\create(ResponseFactory::class),
    'responseStrategy' => DI\create(JsonStrategy::class)
        ->constructor(DI\get(ResponseFactory::class)),
    Router::class => DI\create(Router::class),
        
    SapiEmitter::class => DI\create(SapiEmitter::class),
];