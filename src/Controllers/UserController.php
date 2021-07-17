<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use Twig\Environment;
use Psr\Http\Message\ResponseInterface;
use App\Entities\User;
use Psr\Http\Message\ServerRequestInterface;
use Laminas\Diactoros\Response\RedirectResponse;
use App\Repositories\Services\UserService;

/**
 * Description of UserController
 *
 * @author Hristo
 */
class UserController extends BaseController
{
    private UserService $userService;
    
    public function __construct(Environment $twig, ResponseInterface $response, UserService $userService)
    {
        parent::__construct($twig, $response);
        $this->userService = $userService;
    }
    
    public function index(ServerRequestInterface $request, array $arguments): ResponseInterface
    {
//        $article = $this->em->find(User::class, 16);
//        $article->setUserName('Tractor');
//        $this->em->persist($article);
//        $this->em->flush();
//        $users = array_merge($arguments, ['users' => $dbUsers]);
//        print_r($this->em);
//        $article = $this->userService->findOneById(1);
        $article = $this->userService->all();
        var_dump($article); exit;
        
        return $this->render('home', $article);
    }
    
    public function store(ServerRequestInterface $request, array $arguments): ResponseInterface
    {
        $this->userRepository->add($arguments['name']);
        
        return new RedirectResponse('/hello/'.$arguments['name'], 301);
    }
}
