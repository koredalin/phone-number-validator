<?php

namespace App\Factory;

use DI\Container;
use League\Route\RouteCollectionInterface;
use League\Route\Router;

/**
 * Description of RouteFactory
 *
 * @author Hristo
 */
class RouteFactory
{
    public static function build(Container $container): RouteCollectionInterface
    {
        $route = new Router($container);
        
        return $route;
    }
}
