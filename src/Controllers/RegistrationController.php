<?php

namespace App\Controllers;

use App\Controllers\ApiTransactionSubmitController;
use Psr\Http\Message\ResponseInterface;
use App\Services\Interfaces\RegistrationServiceInterface;
use Psr\Http\Message\ServerRequestInterface;
use App\Entities\PhoneConfirmation;
use App\Controllers\Response\Interfaces\ResponseAssembleInterface;
// Response
use App\Controllers\ResponseStatuses as ResStatus;
use App\Controllers\Response\Models\TransactionSubmitResult as TransactionResponse;
// Exceptions
use \Exception;
use App\Exceptions\NotValidInputException;
use App\Exceptions\SMSConfirmationCodeNotSentException;
use App\Exceptions\AlreadyMadeServiceActionException;

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
        try {
            $phoneConfirmation = $this->registrationService->registrate();
            $responseContent = $this->successResult($phoneConfirmation);
            $responseContent = $this->testing($request, $phoneConfirmation, $responseContent);
        } catch (NotValidInputException | SMSConfirmationCodeNotSentException | AlreadyMadeServiceActionException | Exception $ex) {
            $responseStatusCode = (int)$ex->getCode() > 0 ? (int)$ex->getCode() : ResStatus::INTERNAL_SERVER_ERROR;
            return $this->render($this->failResult($ex), $arguments, $responseStatusCode);
        }
        
        return $this->render($responseContent, $arguments, ResStatus::SUCCESS);
    }
    
    private function successResult(PhoneConfirmation $phoneConfirmation): TransactionResponse
    {
        return $this->result->assembleResponse($phoneConfirmation->getTransaction(), $this->registrationService->getErrors(), true, $this->registrationService->getNextWebPage());
    }
    
    private function testing(ServerRequestInterface $request, PhoneConfirmation $phoneConfirmation, TransactionResponse $responseContent): TransactionResponse
    {
        $parsedRequestBody = \json_decode($request->getBody()->getContents(), true);
        if (
            RETURN_GENERATED_CONFIRMATION_CODE
            && isset($parsedRequestBody[RETURN_GENERATED_CONFIRMATION_CODE_KEY])
            && RETURN_GENERATED_CONFIRMATION_CODE_STR === $parsedRequestBody[RETURN_GENERATED_CONFIRMATION_CODE_KEY] ?? ''
        ) {
            $responseContent->generatedConfirmationCode = $phoneConfirmation->getConfirmationCode();
        }
        
        return $responseContent;
    }
    
    private function failResult(string $exceptionMessage): TransactionResponse
    {
        return $this->result->assembleResponse(null, $exceptionMessage, true, '');
    }
}
