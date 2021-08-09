<?php

namespace App\Controllers;

use App\Controllers\BaseControllerJson;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use App\Services\Interfaces\ResetServiceInterface;

/**
 * Description of ResetController
 *
 * @author Hristo
 */
class ResetController extends BaseControllerJson
{
    private ResetServiceInterface $confirmationService;
    
    public function __construct(
        ResponseInterface $response,
        ResetServiceInterface $confirmationService
    ) {
        parent::__construct($response);
        $this->confirmationService = $confirmationService;
    }
    
    public function resetCode(ServerRequestInterface $request, array $arguments): ResponseInterface
    {
        $transactionId = (int)$arguments['transactionId'] ?? 0;
        $phoneConfirmationAttempt = $this->confirmationService->resetConfirmationCode($transactionId);
        
        $responseArguments = [
            'error' => $this->confirmationService->getErrors(),
            'isSuccess' => $this->confirmationService->isSuccess(),
            'nextWebPage' => $this->confirmationService->getNextWebPage(),
        ];
        
        return $this->render(\json_encode($phoneConfirmationAttempt), $responseArguments);
    }
}
