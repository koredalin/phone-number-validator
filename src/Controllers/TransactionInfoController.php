<?php

namespace App\Controllers;

use App\Controllers\ApiTransactionSubmitController;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use App\Services\Interfaces\TransactionRepositoryServiceInterface;
use App\Entities\Transaction;
use App\Controllers\Response\Interfaces\ResponseAssembleInterface;
// Response
use App\Controllers\ResponseStatuses as ResStatus;

/**
 * Description of ResetController
 *
 * @author Hristo
 */
class TransactionInfoController extends ApiTransactionSubmitController
{
    private TransactionRepositoryServiceInterface $transactionRepositoryService;
    private ResponseAssembleInterface $result;
    
    public function __construct(
        ResponseInterface $response,
        TransactionRepositoryServiceInterface $transactionRepositoryService,
        ResponseAssembleInterface $result
    ) {
        parent::__construct($response);
        $this->transactionRepositoryService = $transactionRepositoryService;
        $this->result = $result;
    }
    
    public function index(ServerRequestInterface $request, array $arguments): ResponseInterface
    {
        $transactionId = (int)$arguments['transactionId'] ?? 0;
        $transaction = $this->transactionRepositoryService->findOneById($transactionId);
        
        $responseContent = is_null($transaction) ? $this->failResult() : $this->successResult($transaction);
        $responseStatus = is_null($transaction) ? ResStatus::NOT_FOUND : ResStatus::SUCCESS;
        
        return $this->render($responseContent, $arguments, $responseStatus);
    }
    
    public function detailedInfo(ServerRequestInterface $request, array $arguments): ResponseInterface
    {
        // Temporary decision
        // TODO Implementation.
        return $this->index($request, $arguments);
    }
    
    private function failResult() {
        return $this->result->assembleResponse(null, false, 'No transaction found.', true, '');
    }
    
    private function successResult(Transaction $transaction) {
        return $this->result->assembleResponse($transaction, true, '', true, '');
    }
}
