<?php

namespace App\Controllers;

use App\Controllers\ApiTransactionSubmitController;
use Psr\Http\Message\ResponseInterface;
use App\Services\Interfaces\RegistrationServiceInterface;
use Psr\Http\Message\ServerRequestInterface;
use App\Entities\PhoneConfirmation;
use App\Controllers\Response\Interfaces\ResponseAssembleInterface;
// Input
use App\Controllers\Input\Models\RegistrationModel;
use App\Controllers\Input\Models\RegistrationModelPhoneCodeNumber;
use App\Controllers\Input\Models\RegistrationModelAssembledPhoneNumber;
// Input Validation
use Symfony\Component\Validator\Validation;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\Validator\ConstraintViolationList;
// Response
use App\Controllers\ResponseStatuses as ResStatus;
use App\Controllers\Response\Models\TransactionSubmitResult as TransactionResponse;
// Exceptions
use \Exception;
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
    
    private ValidatorInterface $validator;
    private ?ConstraintViolationList $formErrors;
    private RegistrationModel $form;
    
    public function __construct(
        ResponseInterface $response,
        RegistrationServiceInterface $registrationService,
        ResponseAssembleInterface $result
    ) {
        parent::__construct($response);
        $this->registrationService = $registrationService;
        $this->result = $result;
        
        $this->validator = Validation::createValidatorBuilder()
            ->enableAnnotationMapping()
            ->addDefaultDoctrineAnnotationReader()
            ->getValidator();
    }
    
    public function registrateFormFromPhoneCodeNumber(ServerRequestInterface $request, array $arguments): ResponseInterface
    {
        $requestBody = $request->getBody()->getContents();
        $this->form = $this->createFormFromPhoneCodeNumber($requestBody);
        if (!$this->isValidForm()) {
            return $this->render($this->failResult($this->getFormErrors()), $arguments, ResStatus::UNPROCESSABLE_ENTITY);
        }
        
        try {
            $phoneConfirmation = $this->registrationService->registratePhoneCodeNumber($this->form);
        } catch (SMSConfirmationCodeNotSentException | AlreadyMadeServiceActionException | Exception $ex) {
            $responseStatusCode = (int)$ex->getCode() > 0 ? (int)$ex->getCode() : ResStatus::INTERNAL_SERVER_ERROR;
            return $this->render($this->failResult($ex), $arguments, $responseStatusCode);
        }
        
        return $this->registrateForm($request, $arguments, $phoneConfirmation);
    }
    
    public function registrateFormFromAssembledPhoneNumber(ServerRequestInterface $request, array $arguments): ResponseInterface
    {
        $requestBody = $request->getBody()->getContents();
        $this->form = $this->createFormFromAssembledPhoneNumber($requestBody);
        if (!$this->isValidForm()) {
            return $this->render($this->failResult($this->getFormErrors()), $arguments, ResStatus::UNPROCESSABLE_ENTITY);
        }
        
        try {
            $phoneConfirmation = $this->registrationService->registrateAssembledPhoneNumber($this->form);
        } catch (SMSConfirmationCodeNotSentException | AlreadyMadeServiceActionException | Exception $ex) {
            $responseStatusCode = (int)$ex->getCode() > 0 ? (int)$ex->getCode() : ResStatus::INTERNAL_SERVER_ERROR;
            return $this->render($this->failResult($ex), $arguments, $responseStatusCode);
        }
        
        return $this->registrateForm($request, $arguments, $phoneConfirmation);
    }
    
    
    private function createFormFromPhoneCodeNumber(string $requestBody): RegistrationModelPhoneCodeNumber
    {
        $parsedRequestBody = \json_decode($requestBody, true);
        $formPhoneCodeNumber = new RegistrationModelPhoneCodeNumber();
        $formPhoneCodeNumber->setEmail($parsedRequestBody['email']);
        $formPhoneCodeNumber->setPhoneCode((int)$parsedRequestBody['phoneCode']);
        $formPhoneCodeNumber->setPhoneNumber((int)$parsedRequestBody['phoneNumber']);
        $formPhoneCodeNumber->setPassword($parsedRequestBody['password']);
        
        return $formPhoneCodeNumber;
    }
    
    
    private function createFormFromAssembledPhoneNumber(string $requestBody): RegistrationModelAssembledPhoneNumber
    {
        $parsedRequestBody = \json_decode($requestBody, true);
        $formAssembledPhoneNumber = new RegistrationModelAssembledPhoneNumber();
        $formAssembledPhoneNumber->setEmail($parsedRequestBody['email']);
        $phoneNumberInput = (string)preg_replace('/[^0-9]/', '', $parsedRequestBody['assembledPhoneNumber']);
        $phoneNumberInt = substr($phoneNumberInput, 0, 1) === '0'
            ? (int)(Country::BG_PHONE_CODE.substr($phoneNumberInput, 1))
            : (int)$phoneNumberInput;
        $formAssembledPhoneNumber->setAssembledPhoneNumber($phoneNumberInt);
        $formAssembledPhoneNumber->setPassword($parsedRequestBody['password']);
        
        return $formAssembledPhoneNumber;
    }
    
    private function isValidForm(): bool
    {
        $this->notSetFormException();
        
        $errors = $this->validator->validate($this->form);
        $this->formErrors = $errors;
        
        return count($errors) == 0;
    }
    
    private function notSetFormException(): void
    {
        if (!isset($this->form)) {
            throw new Exception('Registration form not set yet. Use Registration::createForm() first.');
        }
    }
    
    private function getFormErrors(): string
    {
        $this->notSetFormException();
        
        return (string)$this->formErrors;
    }
    
    /**
     * Needs generated Registration service form first.
     * 
     * @param ServerRequestInterface $request
     * @param array $arguments
     * @return ResponseInterface
     */
    private function registrateForm(ServerRequestInterface $request, array $arguments, PhoneConfirmation $phoneConfirmation): ResponseInterface
    {
        $responseContent = $this->successResult($phoneConfirmation);
        $responseContent = $this->testing($request, $phoneConfirmation, $responseContent);
        
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
