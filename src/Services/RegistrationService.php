<?php

namespace App\Services;

use App\Services\WebPageService;
use App\Services\Interfaces\RegistrationServiceInterface;
use App\Entities\Forms\RegistrationFormAssembledPhoneNumber;
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
// Response
use App\Controllers\ResponseStatuses as ResStatus;

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
    
    private RegistrationFormAssembledPhoneNumber $form;
    
    private ?ConstraintViolationList $formErrors;
    
    private UserRepositoryServiceInterface $userService;
    private PhoneRepositoryServiceInterface $phoneService;
    private TransactionRepositoryServiceInterface $transactionService;
    private PhoneConfirmationRepositoryServiceInterface $phoneConfirmationService;
    private ConfirmationCodeSmsInterface $confirmationCodeSms;

    public function __construct(
        RegistrationFormAssembledPhoneNumber $registrationForm,
        UserRepositoryServiceInterface $userService,
        PhoneRepositoryServiceInterface $phoneService,
        TransactionRepositoryServiceInterface $transactionService,
        PhoneConfirmationRepositoryServiceInterface $phoneConfirmationService,
        ConfirmationCodeSmsInterface $confirmationCodeSms
    ) {
        $this->form = $registrationForm;
        
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
    
    
    public function createForm(string $requestBody): RegistrationFormAssembledPhoneNumber
    {
        $parsedRequestBody = \json_decode($requestBody, true);
        $this->form->setEmail($parsedRequestBody['email']);
//        $this->form->setPhoneCode($parsedRequestBody['phoneCode']);
        $phoneNumberInput = (string)trim($parsedRequestBody['assembledPhoneNumber']);
        $phoneNumberInt = substr($phoneNumberInput, 0, 1) === '0'
            ? (int)Country::BG_PHONE_CODE.substr($phoneNumberInput, 1)
            : (int)$phoneNumberInput;
        $this->form->setAssembledPhoneNumber($phoneNumberInt);
        $this->form->setPassword($parsedRequestBody['password']);
        
        return $this->form;
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
    
    public function registrate(): ?PhoneConfirmation
    {
        if ($this->isFinishedServiceAction) {
            throw new \Exception('The registration is already made.');
        }
        
        $this->notSetFormException();
        if (!$this->isValidForm()) {
            $this->responseStatus = ResStatus::UNPROCESSABLE_ENTITY;
            return null;
        }
        
        $user = $this->getOrCreateByEmail();
        if (is_null($user)) {
            $this->responseStatus = ResStatus::SERVICE_UNAVAILABLE;
            return null;
        }
        
        $phone = $this->getOrCreatePhone();
        if (is_null($phone)) {
            $this->responseStatus = ResStatus::SERVICE_UNAVAILABLE;
            return null;
        }
        
        $transaction = $this->createTransaction($user, $phone);
        if (is_null($transaction)) {
            $this->responseStatus = ResStatus::SERVICE_UNAVAILABLE;
            return null;
        }
        
        $phoneConfirmation = $this->createPhoneConfirmation($transaction);
        if (is_null($phoneConfirmation) || $phoneConfirmation->getId() < 1) {
            $this->responseStatus = ResStatus::SERVICE_UNAVAILABLE;
            return null;
        }
        
        $phoneConfirmationSms = $this->confirmationCodeSms->sendConfirmationCodeMessage($phoneConfirmation->getId());
        if (is_null($phoneConfirmationSms) || $phoneConfirmationSms->getId() < 1) {
            $this->responseStatus = ResStatus::SERVICE_UNAVAILABLE;
            $this->errors .= 'Confirmation code SMS is not sent.';
            return null;
        }
        
        $this->isFinishedServiceAction = true;
        $this->isSuccess = true;
        $this->nextWebPage = self::NEXT_WEB_PAGE_GROUP.'/'.$transaction->getId();
        
        return $phoneConfirmation;
    }
    
    private function getOrCreateByEmail(): ?User
    {
        $user = $this->userService->getOrCreateByEmail($this->form->getEmail());
        if ((int)$user->getId() < 1) {
            return null;
        }
        
        return $user;
    }
    
    private function getOrCreatePhone(): ?Phone
    {
        $phone = $this->phoneService->getOrCreateByAssembledPhoneNumber($this->form->getAssembledPhoneNumber());
        $phoneAnyError = $this->phoneService->getAnyError();
        if (is_null($phone) || $phoneAnyError !== '' || (int)$phone->getId() < 1) {
            $this->errors .= $phoneAnyError;
            return null;
        }
    
        return $phone;
    }
    
    private function createTransaction(User $user, Phone $phone): ?Transaction
    {
        $transaction = $this->transactionService->make($user, $phone, $this->form->getPassword());
        if ((int)$transaction->getId() < 1) {
            return null;
        }
    
        return $transaction;
    }
    
    private function createPhoneConfirmation(Transaction $transaction): ?PhoneConfirmation
    {
        $phoneConfirmation = $this->phoneConfirmationService->getOrCreateByTransactionAwaitingStatus($transaction);
        if ((int)$phoneConfirmation->getId() < 1) {
            return null;
        }
    
        return $phoneConfirmation;
    }
    
    private function notSetFormException(): void
    {
        if (!isset($this->form)) {
            throw new \Exception('Registration form not set yet. Use Registration::createForm() first.');
        }
    }
}
