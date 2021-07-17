<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use Twig\Environment;
use Psr\Http\Message\ResponseInterface;
use App\Entities\User;
use Psr\Http\Message\ServerRequestInterface;
use Laminas\Diactoros\Response\RedirectResponse;
use Doctrine\ORM\EntityManager;

/**
 * Description of UserController
 *
 * @author Hristo
 */
class UserController extends BaseController
{
    private EntityManager $em;
    
    public function __construct(Environment $twig, ResponseInterface $response, EntityManager $em)
    {
        parent::__construct($twig, $response);
        $this->em = $em;
    }
    
    public function index(ServerRequestInterface $request, array $arguments): ResponseInterface
    {
        $dbUsers = $this->em->find(User::class, 1);
//        $users = array_merge($arguments, ['users' => $dbUsers]);
//        print_r($this->em);
        var_dump($dbUsers); exit;
        
        return $this->render('home', $dbUsers);
    }
    
    public function store(ServerRequestInterface $request, array $arguments): ResponseInterface
    {
        $this->userRepository->add($arguments['name']);
        
        return new RedirectResponse('/hello/'.$arguments['name'], 301);
    }
}
