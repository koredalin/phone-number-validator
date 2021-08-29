<?php
//declare(strict_types=1);

namespace App;

use DI\Container;
use League\Route\RouteCollectionInterface;
use League\Route\Router;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
use App\Http\Controllers\GreetingsController;
use App\Controllers\UserController;
use App\Controllers\ConfirmationController;
use App\Controllers\RegistrationController;
use App\Controllers\ResetController;

/**
 * Description of Routes
 *
 * @author Hristo
 */
class Routes
{
    public static function routes(Container $container, Router $router): RouteCollectionInterface
    {
        
        $router->map('GET', URL_SUBFOLDER.'/', function (ServerRequestInterface $request) use ($container): ResponseInterface {
            $response = $container->get('response');
            $response->getBody()->write('<h2>Maskaaaaa</h2>');
            
            return $response;
        });
        
        
        $router->map('GET', URL_SUBFOLDER.'/hello/{name}', GreetingsController::class.'::index');
        $router->map('GET', URL_SUBFOLDER.'/add/{name}', GreetingsController::class.'::store');
        $router->map('GET', URL_SUBFOLDER.'/user', UserController::class.'::index');
//        $router->addRoute('POST', URL_SUBFOLDER.'/registration', PhoneConfirmationController::class.'::index');
        
        
//        $router->group('/api/v1', function ($router) {
            $router->map('POST', URL_SUBFOLDER.'/registration/assembled-phone-number', RegistrationController::class.'::registrateFormWithAssembledPhoneNumber');
            $router->map('POST', URL_SUBFOLDER.'/confirmation/{transactionId}', ConfirmationController::class.'::index');
            $router->map('GET', URL_SUBFOLDER.'/reset-code/{transactionId}', ResetController::class.'::resetCode');
//        })->middleware(new CorsRequest($router));
        
//        print_r($route); exit;
        
        return $router;
    }
}