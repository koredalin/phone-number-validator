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
use App\Controllers\TransactionInfoController;

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
        
        $router->map('POST', URL_SUBFOLDER.'/registration/phone-code-number', RegistrationController::class.'::registrateFormFromPhoneCodeNumber');
        $router->map('POST', URL_SUBFOLDER.'/registration/assembled-phone-number', RegistrationController::class.'::registrateFormFromAssembledPhoneNumber');
            
        $router->map('POST', URL_SUBFOLDER.'/confirmation/{transactionId}', ConfirmationController::class.'::index');
        $router->map('GET', URL_SUBFOLDER.'/reset-code/{transactionId}', ConfirmationController::class.'::resetCode');
        
        $router->map('GET', URL_SUBFOLDER.'/transaction-info/{transactionId}', TransactionInfoController::class.'::index');
        
//        print_r($route); exit;
        
        return $router;
    }
}