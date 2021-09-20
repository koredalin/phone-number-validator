<?php

namespace App\Services;

use App\Services\WebPageService;
use App\Services\Interfaces\RegistrationServiceInterface;
use App\Controllers\Input\Models\RegistrationModel;
use App\Controllers\Input\Models\RegistrationModelPhoneCodeNumber;
use App\Controllers\Input\Models\RegistrationModelAssembledPhoneNumber;
use Symfony\Component\Validator\Validation;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\Validator\ConstraintViolationList;
// Entities
use App\Entities\Country;
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
use App\Exceptions\NotValidInputException;
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
    
    private ValidatorInterface $validator;
    
    private RegistrationModel $form;
    private RegistrationModelPhoneCodeNumber $formPhoneCodeNumber;
    private RegistrationModelAssembledPhoneNumber $formAssembledPhoneNumber;
    
    private ?ConstraintViolationList $formErrors;
    
    private UserRepositoryServiceInterface $userService;
    private PhoneRepositoryServiceInterface $phoneService;
    private TransactionRepositoryServiceInterface $transactionService;
    private PhoneConfirmationRepositoryServiceInterface $phoneConfirmationService;
    private ConfirmationCodeSmsInterface $confirmationCodeSms;

    public function __construct(
        RegistrationModelPhoneCodeNumber $registrationFormPhoneCodeNumber,
        RegistrationModelAssembledPhoneNumber $registrationFormAssembledPhoneNumber,
        UserRepositoryServiceInterface $userService,
        PhoneRepositoryServiceInterface $phoneService,
        TransactionRepositoryServiceInterface $transactionService,
        PhoneConfirmationRepositoryServiceInterface $phoneConfirmationService,
        ConfirmationCodeSmsInterface $confirmationCodeSms
    ) {
        $this->formPhoneCodeNumber = $registrationFormPhoneCodeNumber;
        $this->formAssembledPhoneNumber = $registrationFormAssembledPhoneNumber;
        
        $this->validator = Validation::createValidatorBuilder()
            ->enableAnnotationMapping()
            ->addDefaultDoctrineAnnotationReader()
            ->getValidator();
        $this->formErrors = null;
        $this->userService = $userService;
        $this->phoneService = $phoneService;
        $this->transactionService = $transactionService;
        $this->phoneConfirmationService = $phoneConfirmationService;
        $this->confirmationCodeSms = $confirmationCodeSms;
        $this->setDefaultWebPageProperties();
        $this->nextWebPage = self::CURRENT_WEB_PAGE;
    }
    
    
    public function createFormFromPhoneCodeNumber(string $requestBody): RegistrationModelPhoneCodeNumber
    {
        $parsedRequestBody = \json_decode($requestBody, true);
        $this->formPhoneCodeNumber->setEmail($parsedRequestBody['email']);
        $this->formPhoneCodeNumber->setPhoneCode((int)$parsedRequestBody['phoneCode']);
        $phoneNumberInput = (string)preg_replace('/[^0-9]/', '', $parsedRequestBody['phoneNumber']);
        $this->formPhoneCodeNumber->setPhoneNumber((int)$phoneNumberInput);
        $this->formPhoneCodeNumber->setPassword($parsedRequestBody['password']);
        $this->form = $this->formPhoneCodeNumber;
        
        return $this->formPhoneCodeNumber;
    }
    
    
    public function createFormFromAssembledPhoneNumber(string $requestBody): RegistrationModelAssembledPhoneNumber
    {
        $parsedRequestBody = \json_decode($requestBody, true);
        $this->formAssembledPhoneNumber->setEmail($parsedRequestBody['email']);
        $phoneNumberInput = (string)preg_replace('/[^0-9]/', '', $parsedRequestBody['assembledPhoneNumber']);
        $phoneNumberInt = substr($phoneNumberInput, 0, 1) === '0'
            ? (int)(Country::BG_PHONE_CODE.substr($phoneNumberInput, 1))
            : (int)$phoneNumberInput;
        $this->formAssembledPhoneNumber->setAssembledPhoneNumber($phoneNumberInt);
        $this->formAssembledPhoneNumber->setPassword($parsedRequestBody['password']);
        $this->form = $this->formAssembledPhoneNumber;
        
        return $this->formAssembledPhoneNumber;
    }
    
    public function isValidForm(): bool
    {
        $this->notSetFormException();
        
        $errors = $this->validator->validate($this->form);
        $this->formErrors = $errors;
        
        return count($errors) == 0;
    }
    
    public function getFormErrors(): string
    {
        $this->notSetFormException();
        
        return (string)$this->formErrors;
    }
    
    public function registrate(): PhoneConfirmation
    {
        if ($this->isFinishedServiceAction) {
            throw new AlreadyMadeServiceActionException($this->errors);
        }
        
        $this->notSetFormException();
        if (!$this->isValidForm()) {
            throw new NotValidInputException($this->formErrors->__toString());
        }
        
        $user = $this->getOrCreateByEmail();
        
        $phone = $this->getOrCreatePhone();
        
        $transaction = $this->createTransaction($user, $phone);
        
        $phoneConfirmation = $this->createPhoneConfirmation($transaction);
        
        $phoneConfirmationWithSmsStatus = $this->actionSendSmsConfirmationCode($phoneConfirmation);
        
        $this->isFinishedServiceAction = true;
        $this->nextWebPage = self::NEXT_WEB_PAGE_GROUP.'/'.$transaction->getId();
        
        return $phoneConfirmationWithSmsStatus;
    }
    
    private function notSetFormException(): void
    {
        if (!isset($this->form)) {
            throw new Exception('Registration form not set yet. Use Registration::createForm() first.');
        }
    }
    
    private function getOrCreateByEmail(): User
    {
        $user = $this->userService->getOrCreateByEmail($this->form->getEmail());
        if ((int)$user->getId() < 1) {
            throw new Exception('No User generated. '.$this->errors);
        }
        
        return $user;
    }
    
    private function getOrCreatePhone(): Phone
    {
        $phone = $this->form instanceof RegistrationModelPhoneCodeNumber
            ? $this->phoneService->getOrCreateByPhoneCodeNumber($this->form->getPhoneCode(), $this->form->getPhoneNumber())
            : $this->phoneService->getOrCreateByAssembledPhoneNumber($this->form->getAssembledPhoneNumber());
        $phoneAnyError = $this->phoneService->getAnyError();
        if (is_null($phone) || $phoneAnyError !== '' || (int)$phone->getId() < 1) {
            $this->errors .= $phoneAnyError;
            throw new Exception('No Phone generated. '.$this->errors);
        }
    
        return $phone;
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
