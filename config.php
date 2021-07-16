<?php

use Zend\Diactoros\Response;
use Zend\Diactoros\ServerRequestFactory;

return [
    // Setup request/responce
    'request' => function () {
        return ServerRequestFactory::fromGlobals(
            $_SERVER, $_GET, $_COOKIE, $_FILES
        );
    },
            
    'responce' => new Response(),
];