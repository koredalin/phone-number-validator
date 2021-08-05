<?php

namespace App\Controllers;

use App\Controllers\BaseControllerJson;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use App\Services\Interfaces\ConfirmationServiceInterface;

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
        print_r($phoneConfirmationAttempt); exit;
        if (!$this->confirmationService->isValidForm()) {
            return $this->render($this->confirmationService->getFormErrors(), ['formValidation' => 'failure']);
        }
        
        $phoneConfirmation = $this->confirmationService->registrate();
        if (is_null($phoneConfirmation)) {
            return $this->render($this->confirmationService->getDatabaseErrors(), ['formValidation' => 'success', 'databaseValidation' => 'failure']);
        }
        
        $responseArguments = [
            'formValidation' => 'success',
            'databaseValidation' => 'success',
            'nextWebPage' => $this->confirmationService->getNextWebPage(),
        ];
        
        return $this->render(\json_encode($phoneConfirmation), $responseArguments);
    }
}
