<?php

namespace App;

// Libraries
use DI\Container;
use League\Route\RouteCollectionInterface;
use League\Route\Router;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
// Routes
use App\Controllers\RouteConstants as RC;
// Controllers
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
            $response->getBody()->write('<h2>You are welcome :-)</h2>');
            
            return $response;
        });
        $router->map('GET', URL_SUBFOLDER.'/hello/{name}', GreetingsController::class.'::index');
        $router->map('GET', URL_SUBFOLDER.'/add/{name}', GreetingsController::class.'::store');
        $router->map('GET', URL_SUBFOLDER.'/user', UserController::class.'::index');
        
        /**********************************************************************/
        /******************************* API **********************************/
        // Registration
        $router->map('POST', URL_SUBFOLDER.RC::REGISTRATION.'/phone-code-number', RegistrationController::class.'::registrateFormFromPhoneCodeNumber');
        $router->map('POST', URL_SUBFOLDER.RC::REGISTRATION.'/assembled-phone-number', RegistrationController::class.'::registrateFormFromAssembledPhoneNumber');
        // Confirmation
        $router->map('POST', URL_SUBFOLDER.RC::CONFIRMATION.'/{transactionId}', ConfirmationController::class.'::index');
        $router->map('GET', URL_SUBFOLDER.RC::RESET_CODE.'/{transactionId}', ConfirmationController::class.'::resetCode');
        // Info
        $router->map('GET', URL_SUBFOLDER.RC::TRANSACTION_INFO.'/{transactionId}', TransactionInfoController::class.'::index');
        $router->map('GET', URL_SUBFOLDER.RC::TRANSACTION_DETAILED_INFO.'/{transactionId}', TransactionInfoController::class.'::detailedInfo');
        
        return $router;
    }
}