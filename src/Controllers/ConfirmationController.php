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
    }
    
    public function index(ServerRequestInterface $request, array $arguments): ResponseInterface
    {
        $requestBody = $request->getBody()->getContents();
        $transactionId = (int)$arguments['transactionId'] ?? 0;
        try {
            $phoneConfirmationAttempt = $this->confirmationService->confirmCode($transactionId, $requestBody);
            $responseContent = $this->successResult($phoneConfirmationAttempt);
        } catch (AlreadyMadeServiceActionException | NotFoundTransactionException | AlreadyRegistratedTransactionException | ConfirmationCoolDownException | SMSSuccessNotSentException | WrongConfirmationCodeException $ex) {
            $responseStatusCode = (int)$ex->getCode() > 0 ? (int)$ex->getCode() : ResStatus::INTERNAL_SERVER_ERROR;
            return $this->render($this->failResult($ex), $arguments, $responseStatusCode);
        } catch (\Exception $ex) {
            return $this->render($this->failResult($ex), $arguments, ResStatus::INTERNAL_SERVER_ERROR);
        }
        
        return $this->render($responseContent, $arguments, $this->confirmationService->getResponseStatus());
    }
    
    public function resetCode(ServerRequestInterface $request, array $arguments): ResponseInterface
    {
        $transactionId = (int)$arguments['transactionId'] ?? 0;
        $phoneConfirmationAttempt = $this->codeResetService->resetConfirmationCode($transactionId);
        
        $responseContent = is_null($phoneConfirmationAttempt) ? $this->failCodeResetResult() : $this->successCodeResetResult($phoneConfirmationAttempt);
        
        return $this->render($responseContent, $arguments, $this->codeResetService->getResponseStatus());
    }
    
    private function failResult(string $exceptionMessage): TransactionSubmitResult
    {
        return $this->result->assembleResponse(null, false, $exceptionMessage, true, '');
    }
    
    private function successResult(PhoneConfirmationAttempt $phoneConfirmationAttempt): TransactionSubmitResult
    {
        return $this->result->assembleResponse($phoneConfirmationAttempt->getPhoneConfirmation()->getTransaction(), $this->confirmationService->isSuccess(), $this->confirmationService->getErrors(), true, $this->confirmationService->getNextWebPage());
    }
    
    private function failCodeResetResult(): TransactionSubmitResult
    {
        return $this->result->assembleResponse(null, false, $this->codeResetService->getErrors(), true, $this->codeResetService->getNextWebPage());
    }
    
    private function successCodeResetResult(PhoneConfirmation $phoneConfirmation): TransactionSubmitResult
    {
        return $this->result->assembleResponse($phoneConfirmation->getTransaction(), $this->codeResetService->isSuccess(), $this->codeResetService->getErrors(), true, $this->codeResetService->getNextWebPage());
    }
}
