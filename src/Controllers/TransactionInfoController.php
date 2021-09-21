<?php

namespace App\Controllers;

use App\Controllers\ApiTransactionSubmitController;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use App\Services\Interfaces\TransactionRepositoryServiceInterface;
use App\Entities\Transaction;
use App\Controllers\Response\Interfaces\ResponseAssembleInterface;
// Input
use App\Controllers\Input\Models\DetailedInfoModel;
// Validation
use Symfony\Component\Validator\Validation;
use Symfony\Component\Validator\Validator\ValidatorInterface;
// Response
use App\Controllers\ResponseStatuses as ResStatus;
// Exceptions
use \Exception;
use App\Exceptions\NotFoundTransactionException;
use App\Exceptions\WrongTransactionIdPasswordException;

/**
 * Description of ResetController
 *
 * @author Hristo
 */
class TransactionInfoController extends ApiTransactionSubmitController
{
    private TransactionRepositoryServiceInterface $transactionRepositoryService;
    private ResponseAssembleInterface $result;
    
    private ValidatorInterface $validator;
    
    public function __construct(
        ResponseInterface $response,
        TransactionRepositoryServiceInterface $transactionRepositoryService,
        ResponseAssembleInterface $result
    ) {
        parent::__construct($response);
        $this->transactionRepositoryService = $transactionRepositoryService;
        $this->result = $result;
        
        $this->validator = Validation::createValidatorBuilder()
            ->enableAnnotationMapping()
            ->addDefaultDoctrineAnnotationReader()
            ->getValidator();
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
        // Input mapping, model generation.
        $parsedRequestBodyArr = \json_decode($request->getBody()->getContents(), true);
        $detailedInfoModel = new DetailedInfoModel();
        $detailedInfoModel->setPassword((string)$parsedRequestBodyArr['password']);
        $formErrors = $this->validator->validate($detailedInfoModel);
        if (count($formErrors) > 0) {
            return $this->render($this->failResult((string)$formErrors), $arguments, ResStatus::UNPROCESSABLE_ENTITY);
        }
        // Action.
        $transactionId = (int)$arguments['transactionId'] ?? 0;
        try {
            $transaction = $this->transactionRepositoryService->comparePassword($transactionId, $detailedInfoModel->getPassword());
            $responseContent = $this->successResult($transaction, false);
            $responseStatus = ResStatus::SUCCESS;
        } catch (NotFoundTransactionException | WrongTransactionIdPasswordException | Exception $ex) {
            return $this->render($this->failResult($ex), $arguments, $ex->getCode());
        }
        
        return $this->render($responseContent, $arguments, $responseStatus);
    }
    
    private function failResult($error = 'No transaction found.') {
        return $this->result->assembleResponse(null, $error, true, '');
    }
    
    private function successResult(Transaction $transaction, bool $isRestrictedInfo = true) {
        return $this->result->assembleResponse($transaction, '', $isRestrictedInfo, '');
    }
}
