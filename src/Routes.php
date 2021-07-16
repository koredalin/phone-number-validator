<?php

namespace App;

use DI\Container;
use League\Route\RouteCollectionInterface;
use League\Route\Router;
use Swoole\Http\Response;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;

/**
 * Description of Routes
 *
 * @author Hristo
 */
class Routes
{
    public static function routes(Container $container, Router $route): RouteCollectionInterface
    {
//        print_r($route); exit;
        $route->map('GET', URL_SUBFOLDER.'/', function (ServerRequestInterface $request) use ($container): ResponseInterface { // use ($containerRequest, $containerResponse) {
            $response = $container->get('response');
            $response->getBody()->write('<h2>Maskaaaaa</h2>');
            
            return $response;
        });
//        echo ' ||||||||||||||||||||| '.__LINE__.' ||||||||||||||||||||| ';
//        exit;
        
        return $route;
    }
}
