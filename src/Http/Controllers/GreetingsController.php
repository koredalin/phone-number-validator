<?php

namespace App\Http\Controllers;

use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
use App\Http\Controllers\BaseController;

/**
 * Description of GreetingsController
 *
 * @author Hristo
 */
class GreetingsController extends BaseController
{
    public function index(ServerRequestInterface $request, array $arguments): ResponseInterface
    {
//        var_dump($arguments); exit;
        return $this->render('home', $arguments);
    }
}
