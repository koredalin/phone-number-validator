<?php

namespace App\Controllers;

use App\Controllers\ApiTransactionSubmitController;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use App\Services\Interfaces\ConfirmationServiceInterface;
use App\Services\Interfaces\ResetServiceInterface;
use App\Entities\PhoneConfirmation;
use App\Entities\PhoneConfirmationAttempt;
use App\Controllers\Response\Interfaces\ResponseAssembleInterface;
use App\Controllers\Response\Models\TransactionSubmitResult;
// Input
use App\Controllers\Input\Models\ConfirmationCodeModel;
// Validation
use Symfony\Component\Validator\Validation;
use Symfony\Component\Validator\Validator\ValidatorInterface;
// Response
use App\Controllers\ResponseStatuses as ResStatus;
// Exceptions
use \Exception;
use App\Exceptions\AlreadyMadeServiceActionException;
use App\Exceptions\NotFoundTransactionException;
use App\Exceptions\AlreadyRegistratedTransactionException;
use App\Exceptions\ConfirmationCoolDownException;
use App\Exceptions\SMSSuccessNotSentException;
use App\Exceptions\WrongConfirmationCodeException;

/**
 * Description of ConfirmationController
 *
 * @author Hristo
 */
class ConfirmationController extends ApiTransactionSubmitController
{
    private ResponseAssembleInterface $result;
    private ConfirmationServiceInterface $confirmationService;
    private ResetServiceInterface $codeResetService;
    
    private ValidatorInterface $validator;
    
    public function __construct(
        ResponseInterface $response,
        ConfirmationServiceInterface $confirmationService,
        ResetServiceInterface $codeResetService,
        ResponseAssembleInterface $result
    ) {
        parent::__construct($response);
        $this->confirmationService = $confirmationService;
        $this->codeResetService = $codeResetService;
        $this->result = $result;
        
        $this->validator = Validation::createValidatorBuilder()
            ->enableAnnotationMapping()
            ->addDefaultDoctrineAnnotationReader()
            ->getValidator();
    }
    
    public function index(ServerRequestInterface $request, array $arguments): ResponseInterface
    {
        // Input mapping, model generation.
        $parsedRequestBodyArr = \json_decode($request->getBody()->getContents(), true);
        $confirmationCodeModel = new ConfirmationCodeModel();
        $confirmationCode = is_numeric(trim($parsedRequestBodyArr['confirmationCode'])) ? (int)$parsedRequestBodyArr['confirmationCode'] : $parsedRequestBodyArr['confirmationCode'];
        $confirmationCodeModel->setConfirmationCode($confirmationCode);
        $formErrors = $this->validator->validate($confirmationCodeModel);
        if (count($formErrors) > 0) {
            return $this->render($this->failResult((string)$formErrors), $arguments, ResStatus::UNPROCESSABLE_ENTITY);
        }
        // Action.
        $transactionId = (int)$arguments['transactionId'] ?? 0;
        try {
            $phoneConfirmationAttempt = $this->confirmationService->confirmCode($transactionId, $confirmationCodeModel);
            $responseContent = $this->successResult($phoneConfirmationAttempt);
        } catch (AlreadyMadeServiceActionException | NotFoundTransactionException | AlreadyRegistratedTransactionException | ConfirmationCoolDownException | SMSSuccessNotSentException | WrongConfirmationCodeException | Exception $ex) {
            $responseStatusCode = (int)$ex->getCode() > 0 ? (int)$ex->getCode() : ResStatus::INTERNAL_SERVER_ERROR;
            return $this->render($this->failResult($ex), $arguments, $responseStatusCode);
        }
        // Success.
        return $this->render($responseContent, $arguments, $this->confirmationService->getResponseStatus());
    }
    
    public function resetCode(ServerRequestInterface $request, array $arguments): ResponseInterface
    {
        $transactionId = (int)$arguments['transactionId'] ?? 0;
        try {
            $phoneConfirmationAttempt = $this->codeResetService->resetConfirmationCode($transactionId);
            $responseContent = $this->successCodeResetResult($phoneConfirmationAttempt);
        } catch (AlreadyMadeServiceActionException | NotFoundTransactionException | AlreadyRegistratedTransactionException | ConfirmationResetCoolDownException | SMSConfirmationCodeNotSentException | Exception $ex) {
            $responseStatusCode = (int)$ex->getCode() > 0 ? (int)$ex->getCode() : ResStatus::INTERNAL_SERVER_ERROR;
            return $this->render($this->failCodeResetResult($ex), $arguments, $responseStatusCode);
        }
        
        return $this->render($responseContent, $arguments, $this->codeResetService->getResponseStatus());
    }
    
    private function failResult(string $exceptionMessage): TransactionSubmitResult
    {
        return $this->result->assembleResponse(null, $exceptionMessage, true, '');
    }
    
    private function successResult(PhoneConfirmationAttempt $phoneConfirmationAttempt): TransactionSubmitResult
    {
        return $this->result->assembleResponse($phoneConfirmationAttempt->getPhoneConfirmation()->getTransaction(), $this->confirmationService->getErrors(), true, $this->confirmationService->getNextWebPage());
    }
    
    private function failCodeResetResult(string $exceptionMessage): TransactionSubmitResult
    {
        return $this->result->assembleResponse(null, $exceptionMessage, true, '');
    }
    
    private function successCodeResetResult(PhoneConfirmation $phoneConfirmation): TransactionSubmitResult
    {
        return $this->result->assembleResponse($phoneConfirmation->getTransaction(), $this->codeResetService->getErrors(), true, $this->codeResetService->getNextWebPage());
    }
}
