<?php

namespace App\Controllers;

use App\Controllers\ApiTransactionSubmitController;
use Psr\Http\Message\ResponseInterface;
use App\Services\Interfaces\RegistrationServiceInterface;
use Psr\Http\Message\ServerRequestInterface;
use App\Entities\PhoneConfirmation;
use App\Controllers\Response\Interfaces\ResponseAssembleInterface;

/**
 * Description of RegistrationFormController
 *
 * @author Hristo
 */
class RegistrationController extends ApiTransactionSubmitController
{
    private RegistrationServiceInterface $registrationService;
    private ResponseAssembleInterface $result;
    
    public function __construct(
        ResponseInterface $response,
        RegistrationServiceInterface $registrationService,
        ResponseAssembleInterface $result
    ) {
        parent::__construct($response);
        $this->registrationService = $registrationService;
        $this->result = $result;
    }
    
    public function registrateFormFromPhoneCodeNumber(ServerRequestInterface $request, array $arguments): ResponseInterface
    {
        $requestBody = $request->getBody()->getContents();
        $this->registrationService->createFormFromPhoneCodeNumber($requestBody);
        
        return $this->registrateForm($request, $arguments);
    }
    
    public function registrateFormFromAssembledPhoneNumber(ServerRequestInterface $request, array $arguments): ResponseInterface
    {
        $requestBody = $request->getBody()->getContents();
        $this->registrationService->createFormFromAssembledPhoneNumber($requestBody);
        
        return $this->registrateForm($request, $arguments);
    }
    
    /**
     * Needs generated Registration service form first.
     * 
     * @param ServerRequestInterface $request
     * @param array $arguments
     * @return ResponseInterface
     */
    private function registrateForm(ServerRequestInterface $request, array $arguments): ResponseInterface
    {
        if (!$this->registrationService->isValidForm()) {
            return $this->render($this->failResult(), $arguments, $this->registrationService->getResponseStatus());
        }
        
        $phoneConfirmation = $this->registrationService->registrate();
        if (is_null($phoneConfirmation)) {
            return $this->render($this->failResult(), $arguments, $this->registrationService->getResponseStatus());
        }
        
        $responseContent = $this->successResult($phoneConfirmation);
        
        $parsedRequestBody = \json_decode($request->getBody()->getContents(), true);
        if (
            RETURN_GENERATED_CONFIRMATION_CODE
            && isset($parsedRequestBody[RETURN_GENERATED_CONFIRMATION_CODE_KEY])
            && RETURN_GENERATED_CONFIRMATION_CODE_STR === $parsedRequestBody[RETURN_GENERATED_CONFIRMATION_CODE_KEY] ?? ''
        ) {
            $responseContent->generatedConfirmationCode = $phoneConfirmation->getConfirmationCode();
        }
        
        return $this->render($responseContent, $arguments, $this->registrationService->getResponseStatus());
    }
    
    private function failResult() {
        return $this->result->assembleResponse(null, false, $this->registrationService->getErrors(), true, $this->registrationService->getNextWebPage());
    }
    
    private function successResult(PhoneConfirmation $phoneConfirmation) {
        return $this->result->assembleResponse($phoneConfirmation->getTransaction(), $this->registrationService->isSuccess(), $this->registrationService->getErrors(), true, $this->registrationService->getNextWebPage());
    }
}
