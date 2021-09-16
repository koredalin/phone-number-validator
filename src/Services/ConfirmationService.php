<?php

namespace App\Services;

use App\Services\WebPageService;
use App\Services\Interfaces\ConfirmationServiceInterface;
// Routes
use App\Controllers\RouteConstants as RC;
// Entities
use App\Entities\Transaction;
use App\Entities\PhoneConfirmation;
use App\Entities\PhoneConfirmationAttempt;
// Repositories
use App\Repositories\Interfaces\TransactionRepositoryInterface;
use App\Repositories\Interfaces\PhoneConfirmationRepositoryInterface;
// Repository Services
use App\Services\Interfaces\PhoneConfirmationAttemptRepositoryServiceInterface;
use App\Common\Interfaces\DateTimeManagerInterface;
// SMS
use App\Services\Interfaces\SuccessSmsInterface;
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
 * Phone number confirmation by confirmation code
 *
 * @author Hristo
 */
class ConfirmationService extends WebPageService implements ConfirmationServiceInterface
{
    const CURRENT_WEB_PAGE_GROUP = RC::CONFIRMATION;
    const NEXT_WEB_PAGE = RC::TRANSACTION_INFO;
    const COOL_DOWN_CONFIRMATION_ATTEMPTS_NUMBER = 3;
    const COOL_DOWN_MINUTES = 1;
    
    private TransactionRepositoryInterface $transactionRepository;
    private PhoneConfirmationRepositoryInterface $phoneConfirmationRepository;
    private PhoneConfirmationAttemptRepositoryServiceInterface $phoneConfirmationAttemptService;
    private DateTimeManagerInterface $dtManager;
    private SuccessSmsInterface $successSms;

    public function __construct(
        TransactionRepositoryInterface $transactionService,
        PhoneConfirmationRepositoryInterface $phoneConfirmationService,
        PhoneConfirmationAttemptRepositoryServiceInterface $phoneConfirmationAttemptService,
        DateTimeManagerInterface $dtManager,
        SuccessSmsInterface $successSms
    ) {
        $this->transactionRepository = $transactionService;
        $this->phoneConfirmationRepository = $phoneConfirmationService;
        $this->phoneConfirmationAttemptService = $phoneConfirmationAttemptService;
        $this->dtManager = $dtManager;
        $this->successSms = $successSms;
        $this->setDefaultWebPageProperties();
    }
    
    public function confirmCode(int $transactionId, string $requestBody): ?PhoneConfirmationAttempt
    {
        if ($this->isFinishedServiceAction) {
            throw new AlreadyMadeServiceActionException('The code confirmation already made.');
        }
        
        $this->nextWebPage = self::CURRENT_WEB_PAGE_GROUP.'/'.$transactionId;
        $this->isFinishedServiceAction = true;
        
        $transaction = $this->transactionRepository->findOneById($transactionId);
        if (is_null($transaction)) {
            throw new NotFoundTransactionException('No such transaction.');
        }
        
        if ($transaction->getStatus() === Transaction::STATUS_CONFIRMED) {
            $this->nextWebPage = self::NEXT_WEB_PAGE.'/'.$transactionId;
            throw new AlreadyRegistratedTransactionException('The transaction is registrated.');
        }
        
        $phoneConfirmation = $this->phoneConfirmationRepository->findLastByTransactionAwaitingStatus($transaction);
        if (is_null($phoneConfirmation)) {
            throw new Exception('Not found PhoneConfirmation object. '.$this->errors);
        }
        
        if ($phoneConfirmation->getStatus() === PhoneConfirmation::STATUS_ABANDONED) {
            throw new Exception('PhoneConfirmation status abandoned is not possible in such cases. transactionId: '.$transactionId.'. phoneConfirmationId: '.$phoneConfirmation->getId().'.');
        }
        
        $parsedRequestBody = \json_decode($requestBody, true);
        $inputConfirmationCode = (int)$parsedRequestBody['confirmationCode'];
        $this->actionCoolDown($inputConfirmationCode, $phoneConfirmation);
        
        $phoneConfirmationAttempt = $this->actionFinalConfirmation($inputConfirmationCode, $transaction, $phoneConfirmation);
            
        return $phoneConfirmationAttempt;
    }
    
    private function actionCoolDown(int $inputConfirmationCode, PhoneConfirmation $phoneConfirmation): void
    {
        $phoneConfirmationAttempts = $this->phoneConfirmationAttemptService->findAllByPhoneConfirmationNoCoolDownDesc($phoneConfirmation);
        $lastAttemptTime = count($phoneConfirmationAttempts) > 0 ? $phoneConfirmationAttempts->first()->getCreatedAt() : null;
        if (
            count($phoneConfirmationAttempts) > 1
            && count($phoneConfirmationAttempts) % self::COOL_DOWN_CONFIRMATION_ATTEMPTS_NUMBER == 0
            && $lastAttemptTime->add(new \DateInterval('PT'.self::COOL_DOWN_MINUTES.'M')) > $this->dtManager->now()
        ) {
            $this->phoneConfirmationAttemptService->createByPhoneConfirmationInputConfirmationCode($phoneConfirmation, $inputConfirmationCode, true);
            $this->errors .= 'Minimum interval before next confirmation code attempt: '.self::COOL_DOWN_MINUTES.' minutes.';
            throw new ConfirmationCoolDownException($this->errors);
        }
    }
    
    private function actionFinalConfirmation(int $inputConfirmationCode, Transaction $transaction, PhoneConfirmation $phoneConfirmation): PhoneConfirmationAttempt
    {
        $phoneConfirmationAttempt = $this->phoneConfirmationAttemptService->createByPhoneConfirmationInputConfirmationCode($phoneConfirmation, $inputConfirmationCode);
        if (
            $phoneConfirmationAttempt instanceof PhoneConfirmationAttempt
            && $phoneConfirmationAttempt->getStatus() === PhoneConfirmationAttempt::STATUS_CONFIRMED
        ) {
            $this->actionSendSmsSuccess($transaction);
        
            $this->nextWebPage = self::NEXT_WEB_PAGE.'/'.(int)$transaction->getId();
            $this->setPhoneConfirmationSuccess($phoneConfirmation);
            $this->setTransactionSuccess($transaction);
        } else {
            $this->errors .= 'Wrong confirmation code.';
            throw new WrongConfirmationCodeException($this->errors);
        }
        
        return $phoneConfirmationAttempt;
    }
    
    private function actionSendSmsSuccess(Transaction $transaction): void
    {
        $transactionSuccessSms = $this->successSms->sendSuccessMessage($transaction->getId());
        if (is_null($transactionSuccessSms) || $transactionSuccessSms->getId() < 1) {
            $this->responseStatus = ResStatus::SERVICE_UNAVAILABLE;
            $this->errors .= 'Transaction success SMS is not sent.';
            throw new SMSSuccessNotSentException($this->errors);
        }
    }
    
    private function setPhoneConfirmationSuccess(PhoneConfirmation $phoneConfirmation): void
    {
        $phoneConfirmation->setConfirmedAt($this->dtManager->now());
        $phoneConfirmation->setStatus(PhoneConfirmation::STATUS_CONFIRMED);
        $phoneConfirmation->setUpdatedAt($this->dtManager->now());
        $this->phoneConfirmationRepository->save($phoneConfirmation);
    }
    
    private function setTransactionSuccess(Transaction $transaction): void
    {
        $transaction->setConfirmedAt($this->dtManager->now());
        $transaction->setStatus(Transaction::STATUS_CONFIRMED);
        $transaction->setUpdatedAt($this->dtManager->now());
        $this->transactionRepository->save($transaction);
    }
}