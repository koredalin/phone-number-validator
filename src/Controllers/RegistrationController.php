<?php

namespace App\Controllers;

use App\Controllers\BaseControllerJson;
use Psr\Http\Message\ResponseInterface;
use App\Services\Interfaces\RegistrationServiceInterface;
use Psr\Http\Message\ServerRequestInterface;
use App\Controllers\ResponseStatuses as ResStatus;

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
        $response = ['isSuccess' => false];
        if (!$this->registrationService->isValidForm()) {
            return $this->render($response, $this->getServiceErrorsNextWebPage(), $this->registrationService->getResponseStatus());
        }
        
        $phoneConfirmation = $this->registrationService->registrate();
        if (is_null($phoneConfirmation)) {
            return $this->render($response, $this->getServiceErrorsNextWebPage(), $this->registrationService->getResponseStatus());
        }
        
        $response['isSuccess'] = $this->registrationService->isSuccess();
        $parsedRequestBody = \json_decode($requestBody, true);
        if (
            RETURN_GENERATED_CONFIRMATION_CODE
            && isset($parsedRequestBody[RETURN_GENERATED_CONFIRMATION_CODE_KEY])
            && RETURN_GENERATED_CONFIRMATION_CODE_STR === $parsedRequestBody[RETURN_GENERATED_CONFIRMATION_CODE_KEY] ?? ''
        ) {
            $response[GENERATED_CONFIRMATION_CODE_KEY] = $phoneConfirmation->getConfirmationCode();
        }
        
        return $this->render($response, $this->getServiceErrorsNextWebPage(), $this->registrationService->getResponseStatus());
    }
    
    private function getServiceErrorsNextWebPage(): array
    {
        return [
            'errors' => $this->registrationService->getErrors(),
            'nextWebPage' => $this->registrationService->getNextWebPage(),
        ];
    }
}
