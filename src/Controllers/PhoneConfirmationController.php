<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use Twig\Environment;
use Psr\Http\Message\ResponseInterface;
use App\Repositories\Services\UserRepositoryService;
use App\Repositories\Services\PhoneRepositoryService;
use App\Repositories\Services\TransactionRepositoryService;
use App\Repositories\Services\PhoneConfirmationRepositoryService;
use App\Repositories\Services\PhoneConfirmationAttemptRepositoryService;
use Psr\Http\Message\ServerRequestInterface;
use Laminas\Diactoros\Response\RedirectResponse;

/**
 * Description of PhoneConfirmationController
 *
 * @author Hristo
 */
class PhoneConfirmationController extends BaseController
{
    private UserRepositoryService $userService;
    private PhoneRepositoryService $phoneService;
    private TransactionRepositoryService $transactionRepositoryService;
    private PhoneConfirmationRepositoryService $phoneConfirmationService;
    
    public function __construct(Environment $twig, ResponseInterface $response, UserRepositoryService $userService, PhoneRepositoryService $phoneService, TransactionRepositoryService $transactionService, PhoneConfirmationRepositoryService $phoneConfirmationService)
    {
        echo __LINE__.' |||||||||||| '; exit;
        parent::__construct($twig, $response);
        $this->userService = $userService;
        $this->phoneService = $phoneService;
        $this->transactionRepositoryService = $transactionService;
        $this->phoneConfirmationService = $phoneConfirmationService;
    }
    
    public function index(ServerRequestInterface $request, array $arguments): ResponseInterface
    {
//        print_r($arguments);
//        echo __LINE__.' |||||||||||| '; exit;
//        $article = $this->em->find(Transaction::class, 16);
//        $article->setTransactionName('Tractor');
//        $this->em->persist($article);
//        $this->em->flush();
//        $transactions = array_merge($arguments, ['transactions' => $dbTransactions]);
//        print_r($this->em);
//        $article = $this->transactionRepositoryService->findOneById(1);
//        $article = $this->transactionQueryService->all();
//        print_r($article); exit;
        
        return $this->render('registration.html', []);
    }
    
    public function store(ServerRequestInterface $request, array $arguments): ResponseInterface
    {
        $this->transactionRepository->add($arguments['name']);
        
        return new RedirectResponse('/hello/'.$arguments['name'], 301);
    }
}
