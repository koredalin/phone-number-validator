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
        $this->confirmationService->resetConfirmationCode($transactionId);
        
        $response = ['isSuccess' => $this->confirmationService->isSuccess()];
        $responseArguments = [
            'error' => $this->confirmationService->getErrors(),
            'nextWebPage' => $this->confirmationService->getNextWebPage(),
        ];
        
        return $this->render($response, $responseArguments);
    }
}
