<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use Twig\Environment;
use Psr\Http\Message\ResponseInterface;
use App\Services\UserRepositoryService;
use App\Services\PhoneRepositoryService;
use App\Services\TransactionRepositoryService;
use App\Services\PhoneConfirmationRepositoryService;
use App\Services\PhoneConfirmationAttemptRepositoryService;
use Psr\Http\Message\ServerRequestInterface;
use Laminas\Diactoros\Response\RedirectResponse;

/**
 * Description of ConfirmationController
 *
 * @author Hristo
 */
class ConfirmationController extends BaseController
{
    private RegistrationServiceInterface $registrationService;
    
    public function __construct(
        ResponseInterface $response,
        RegistrationServiceInterface $registrationService
    ) {
        parent::__construct($response);
        $this->registrationService = $registrationService;
    }
    
    public function index(ServerRequestInterface $request, array $arguments): ResponseInterface
    {
        $requestBody = $request->getBody()->getContents();
        $this->registrationService->createForm($requestBody);
        if (!$this->registrationService->isValidForm()) {
            return $this->render($this->registrationService->getFormErrors(), ['formValidation' => 'failure']);
        }
        
        $phoneConfirmation = $this->registrationService->registrate();
        if (is_null($phoneConfirmation)) {
            return $this->render($this->registrationService->getDatabaseErrors(), ['formValidation' => 'success', 'databaseValidation' => 'failure']);
        }
        
        $responseArguments = [
            'formValidation' => 'success',
            'databaseValidation' => 'success',
            'nextWebPage' => $this->registrationService->getNextWebPage(),
        ];
        
        return $this->render(\json_encode($phoneConfirmation), $responseArguments);
    }
}
