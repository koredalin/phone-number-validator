<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use Twig\Environment;
use Psr\Http\Message\ResponseInterface;
use App\Users\UserRepository;
use Psr\Http\Message\ServerRequestInterface;
use Laminas\Diactoros\Response\RedirectResponse;

/**
 * Description of GreetingsController
 *
 * @author Hristo
 */
class GreetingsController extends BaseController
{
    private UserRepository $userRepository;
    
    public function __construct(Environment $twig, ResponseInterface $response, UserRepository $userRepository)
    {
        parent::__construct($twig, $response);
        $this->userRepository = $userRepository;
    }
    
    public function index(ServerRequestInterface $request, array $arguments): ResponseInterface
    {
        $dbUsers = $this->userRepository->getAll();
        $users = array_merge($arguments, ['users' => $dbUsers]);
//        print_r($users); exit;
        
        return $this->render('home', $users);
    }
    
    public function store(ServerRequestInterface $request, array $arguments): ResponseInterface
    {
        $this->userRepository->add($arguments['name']);
        
        return new RedirectResponse('/hello/'.$arguments['name'], 301);
    }
}
