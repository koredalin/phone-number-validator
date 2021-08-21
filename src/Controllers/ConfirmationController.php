<?php

namespace App\Controllers;

use App\Controllers\BaseControllerJson;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use App\Services\Interfaces\ConfirmationServiceInterface;
use App\Entities\PhoneConfirmationAttempt;

/**
 * Description of ConfirmationController
 *
 * @author Hristo
 */
class ConfirmationController extends BaseControllerJson
{
    private ConfirmationServiceInterface $confirmationService;
    
    public function __construct(
        ResponseInterface $response,
        ConfirmationServiceInterface $confirmationService
    ) {
        parent::__construct($response);
        $this->confirmationService = $confirmationService;
    }
    
    public function index(ServerRequestInterface $request, array $arguments): ResponseInterface
    {
        $requestBody = $request->getBody()->getContents();
        $transactionId = (int)$arguments['transactionId'] ?? 0;
        $phoneConfirmationAttempt = $this->confirmationService->confirmCode($transactionId, $requestBody);
        $response = ['isSuccess' => $this->confirmationService->isSuccess()];
        if ($phoneConfirmationAttempt instanceof PhoneConfirmationAttempt) {
            $response = array_merge($response, $this->getRestrictedEmailAndPhoneNumber($phoneConfirmationAttempt->getPhoneConfirmation()->getTransaction()));
        }
        
        $responseArguments = [
            'errors' => $this->confirmationService->getErrors(),
            'nextWebPage' => $this->confirmationService->getNextWebPage(),
        ];
        
        return $this->render($response, $responseArguments, $this->confirmationService->getResponseStatus());
    }
}
