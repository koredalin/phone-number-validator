<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use Twig\Environment;
use Psr\Http\Message\ResponseInterface;
use App\Entities\Transaction;
use Psr\Http\Message\ServerRequestInterface;
use Laminas\Diactoros\Response\RedirectResponse;
use App\Repositories\Services\TransactionRepositoryService;
use App\Queries\Services\TransactionQueryService;

/**
 * Description of UserController
 *
 * @author Hristo
 */
class UserController extends BaseController
{
    private TransactionRepositoryService $userRepositoryService;
    private TransactionQueryService $userQueryService;
    
    public function __construct(Environment $twig, ResponseInterface $response, TransactionRepositoryService $userService, TransactionQueryService $userQueryService)
    {
        parent::__construct($twig, $response);
        $this->userRepositoryService = $userService;
        $this->userQueryService = $userQueryService;
    }
    
    public function index(ServerRequestInterface $request, array $arguments): ResponseInterface
    {
//        $article = $this->em->find(User::class, 16);
//        $article->setUserName('Tractor');
//        $this->em->persist($article);
//        $this->em->flush();
//        $users = array_merge($arguments, ['users' => $dbUsers]);
//        print_r($this->em);
//        $article = $this->userRepositoryService->findOneById(1);
        $article = $this->userQueryService->all();
        print_r($article); exit;
        
        return $this->render('home', $article);
    }
    
    public function store(ServerRequestInterface $request, array $arguments): ResponseInterface
    {
        $this->userRepository->add($arguments['name']);
        
        return new RedirectResponse('/hello/'.$arguments['name'], 301);
    }
}
