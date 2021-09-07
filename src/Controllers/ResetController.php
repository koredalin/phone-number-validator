<?php

namespace App\Controllers;

use App\Controllers\ApiTransactionSubmitController;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use App\Services\Interfaces\ResetServiceInterface;
use App\Entities\PhoneConfirmation;
use App\Controllers\Response\Interfaces\ResponseAssembleInterface;

/**
 * Description of ResetController
 *
 * @author Hristo
 */
class ResetController extends ApiTransactionSubmitController
{
    private ResetServiceInterface $confirmationService;
    private ResponseAssembleInterface $result;
    
    public function __construct(
        ResponseInterface $response,
        ResetServiceInterface $confirmationService,
        ResponseAssembleInterface $result
    ) {
        parent::__construct($response);
        $this->confirmationService = $confirmationService;
        $this->result = $result;
    }
    
    public function resetCode(ServerRequestInterface $request, array $arguments): ResponseInterface
    {
        $transactionId = (int)$arguments['transactionId'] ?? 0;
        $phoneConfirmation = $this->confirmationService->resetConfirmationCode($transactionId);
        
        $responseContent = is_null($phoneConfirmation) ? $this->failResult() : $this->successResult($phoneConfirmation);
        
        return $this->render($responseContent, $arguments, $this->confirmationService->getResponseStatus());
    }
    
    private function failResult() {
        return $this->result->assembleResponse(null, false, $this->confirmationService->getErrors(), true, $this->confirmationService->getNextWebPage());
    }
    
    private function successResult(PhoneConfirmation $phoneConfirmation) {
        return $this->result->assembleResponse($phoneConfirmation->getTransaction(), $this->confirmationService->isSuccess(), $this->confirmationService->getErrors(), true, $this->confirmationService->getNextWebPage());
    }
}
