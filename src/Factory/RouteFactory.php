<?php

namespace App\Factory;

use DI\Container;
use League\Route\RouteCollectionInterface;
use League\Route\Router;
use App\Routes;

/**
 * Description of RouteFactory
 *
 * @author Hristo
 */
class RouteFactory
{
    public static function build(Container $container): RouteCollectionInterface
    {
        $route = $container->get(Router::class);
//        $route->setStrategy($container->get('responseStrategy'));
        
//        $route = new Router($container);
        // print_r($route);
        
        Routes::routes($container, $route);
//        echo ' ||||||||||||||||||||| '.__LINE__.' ||||||||||||||||||||| ';
//        exit;
        
        return $route;
    }
}
