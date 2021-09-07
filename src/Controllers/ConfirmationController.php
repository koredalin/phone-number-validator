<?php

namespace App\Controllers;

use App\Controllers\ApiTransactionSubmitController;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use App\Services\Interfaces\ConfirmationServiceInterface;
use App\Entities\PhoneConfirmationAttempt;
use App\Controllers\Response\Interfaces\ResponseAssembleInterface;

/**
 * Description of ConfirmationController
 *
 * @author Hristo
 */
class ConfirmationController extends ApiTransactionSubmitController
{
    private ConfirmationServiceInterface $confirmationService;
    private ResponseAssembleInterface $result;
    
    public function __construct(
        ResponseInterface $response,
        ConfirmationServiceInterface $confirmationService,
        ResponseAssembleInterface $result
    ) {
        parent::__construct($response);
        $this->confirmationService = $confirmationService;
        $this->result = $result;
    }
    
    public function index(ServerRequestInterface $request, array $arguments): ResponseInterface
    {
        $requestBody = $request->getBody()->getContents();
        $transactionId = (int)$arguments['transactionId'] ?? 0;
        $phoneConfirmationAttempt = $this->confirmationService->confirmCode($transactionId, $requestBody);
        $responseContent = is_null($phoneConfirmationAttempt) ? $this->failResult() : $this->successResult($phoneConfirmationAttempt);
        
        return $this->render($responseContent, $arguments, $this->confirmationService->getResponseStatus());
    }
    
    private function failResult() {
        return $this->result->assembleResponse(null, false, $this->confirmationService->getErrors(), true, $this->confirmationService->getNextWebPage());
    }
    
    private function successResult(PhoneConfirmationAttempt $phoneConfirmationAttempt) {
        return $this->result->assembleResponse($phoneConfirmationAttempt->getPhoneConfirmation()->getTransaction(), $this->confirmationService->isSuccess(), $this->confirmationService->getErrors(), true, $this->confirmationService->getNextWebPage());
    }
}
