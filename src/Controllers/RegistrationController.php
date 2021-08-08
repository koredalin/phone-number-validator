<?php

namespace App\Controllers;

use App\Controllers\BaseControllerJson;
use Psr\Http\Message\ResponseInterface;
use App\Services\Interfaces\RegistrationServiceInterface;
use Psr\Http\Message\ServerRequestInterface;

/**
 * Description of RegistrationFormController
 *
 * @author Hristo
 */
class RegistrationController extends BaseControllerJson
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
            return $this->render($this->registrationService->getErrors(), ['formValidation' => 'success', 'databaseValidation' => 'failure']);
        }
        
        $responseArguments = [
            'errors' => $this->registrationService->getErrors(),
            'isSuccess' => $this->registrationService->isSuccess(),
            'nextWebPage' => $this->registrationService->getNextWebPage(),
        ];
        
        return $this->render(\json_encode($phoneConfirmation), $responseArguments);
    }
}
