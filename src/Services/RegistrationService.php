<?php

namespace App\Services;

use App\Services\WebPageService;
use App\Services\Interfaces\RegistrationServiceInterface;
use App\Controllers\Input\Models\RegistrationModel;
use App\Controllers\Input\Models\RegistrationModelPhoneCodeNumber;
use App\Controllers\Input\Models\RegistrationModelAssembledPhoneNumber;
// Entities
use App\Entities\User;
use App\Entities\Phone;
use App\Entities\Transaction;
use App\Entities\PhoneConfirmation;
// Repository Services
use App\Services\Interfaces\UserRepositoryServiceInterface;
use App\Services\Interfaces\PhoneRepositoryServiceInterface;
use App\Services\Interfaces\TransactionRepositoryServiceInterface;
use App\Services\Interfaces\PhoneConfirmationRepositoryServiceInterface;
// SMS
use App\Services\Interfaces\ConfirmationCodeSmsInterface;
// Exceptions
use \Exception;
use App\Exceptions\SMSConfirmationCodeNotSentException;
use App\Exceptions\AlreadyMadeServiceActionException;

/**
 * Description of Registration
 *
 * @author Hristo
 */
class RegistrationService extends WebPageService implements RegistrationServiceInterface
{
    const CURRENT_WEB_PAGE = '/registration';
    private const NEXT_WEB_PAGE_GROUP = '/confirmation';
    
    private RegistrationModel $form;
    private RegistrationModelPhoneCodeNumber $formPhoneCodeNumber;
    private RegistrationModelAssembledPhoneNumber $formAssembledPhoneNumber;
    
    private UserRepositoryServiceInterface $userService;
    private PhoneRepositoryServiceInterface $phoneService;
    private TransactionRepositoryServiceInterface $transactionService;
    private PhoneConfirmationRepositoryServiceInterface $phoneConfirmationService;
    private ConfirmationCodeSmsInterface $confirmationCodeSms;

    public function __construct(
        UserRepositoryServiceInterface $userService,
        PhoneRepositoryServiceInterface $phoneService,
        TransactionRepositoryServiceInterface $transactionService,
        PhoneConfirmationRepositoryServiceInterface $phoneConfirmationService,
        ConfirmationCodeSmsInterface $confirmationCodeSms
    ) {
        $this->userService = $userService;
        $this->phoneService = $phoneService;
        $this->transactionService = $transactionService;
        $this->phoneConfirmationService = $phoneConfirmationService;
        $this->confirmationCodeSms = $confirmationCodeSms;
        $this->setDefaultWebPageProperties();
        $this->nextWebPage = self::CURRENT_WEB_PAGE;
    }
    
    
    public function registratePhoneCodeNumber(RegistrationModelPhoneCodeNumber $form): PhoneConfirmation
    {
        if ($this->isFinishedServiceAction) {
            throw new AlreadyMadeServiceActionException('Registration');
        }
        
        $this->form = $form;
        try {
            $phone = $this->phoneService->getOrCreateByPhoneCodeNumber($this->form->getPhoneCode(), $this->form->getPhoneNumber());
        } catch (Exception $ex) {
            throw new Exception('No Phone generated. '.$ex.' '.$this->phoneService->getAnyError());
        }
        
        return $this->registrate($phone);
    }
    
    public function registrateAssembledPhoneNumber(RegistrationModelAssembledPhoneNumber $form): PhoneConfirmation
    {
        if ($this->isFinishedServiceAction) {
            throw new AlreadyMadeServiceActionException('Registration');
        }
        
        $this->form = $form;
        try {
            $phone = $this->phoneService->getOrCreateByAssembledPhoneNumber($this->form->getAssembledPhoneNumber());
        } catch (Exception $ex) {
            throw new Exception('No Phone generated. '.$ex.' '.$this->phoneService->getAnyError());
        }
        
        return $this->registrate($phone);
    }
    
    public function registrate(Phone $phone): PhoneConfirmation
    {
        
        $user = $this->getOrCreateByEmail();
        
        $transaction = $this->createTransaction($user, $phone);
        
        $phoneConfirmation = $this->createPhoneConfirmation($transaction);
        
        $phoneConfirmationWithSmsStatus = $this->actionSendSmsConfirmationCode($phoneConfirmation);
        
        $this->isFinishedServiceAction = true;
        $this->nextWebPage = self::NEXT_WEB_PAGE_GROUP.'/'.$transaction->getId();
        
        return $phoneConfirmationWithSmsStatus;
    }
    
    private function getOrCreateByEmail(): User
    {
        $user = $this->userService->getOrCreateByEmail($this->form->getEmail());
        if ((int)$user->getId() < 1) {
            throw new Exception('No User generated. '.$this->errors);
        }
        
        return $user;
    }
    
    private function createTransaction(User $user, Phone $phone): Transaction
    {
        $transaction = $this->transactionService->make($user, $phone, $this->form->getPassword());
        if ((int)$transaction->getId() < 1) {
            throw new Exception('No Transaction generated. '.$this->errors);
        }
    
        return $transaction;
    }
    
    private function createPhoneConfirmation(Transaction $transaction): PhoneConfirmation
    {
        $phoneConfirmation = $this->phoneConfirmationService->getOrCreateByTransactionAwaitingStatus($transaction);
        if ((int)$phoneConfirmation->getId() < 1) {
            throw new Exception('No PhoneConfirmation generated. '.$this->errors);
        }
    
        return $phoneConfirmation;
    }
    
    private function actionSendSmsConfirmationCode(PhoneConfirmation $phoneConfirmation): PhoneConfirmation
    {
        $phoneConfirmationSms = $this->confirmationCodeSms->sendConfirmationCodeMessage($phoneConfirmation->getId());
        if (is_null($phoneConfirmationSms) || $phoneConfirmationSms->getId() < 1) {
            throw new SMSConfirmationCodeNotSentException($this->errors);
        }
        
        return $phoneConfirmationSms;
    }
}
