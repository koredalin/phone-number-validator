<?php

namespace App\Factory;

use DI\Container;
//use DI\get;
use League\Route\RouteCollectionInterface;
use League\Route\Strategy\ApplicationStrategy;
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
        $strategy = $container->get(ApplicationStrategy::class)->setContainer($container);
        $router = $container->get(Router::class)->setStrategy($strategy);
        Routes::routes($container, $router);
        
        return $router;
    }
}
