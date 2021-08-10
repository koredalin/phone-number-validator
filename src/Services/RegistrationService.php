<?php

namespace App\Services;

use App\Services\Interfaces\RegistrationServiceInterface;
use App\Entities\Forms\RegistrationForm;
use Symfony\Component\Validator\Validation;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\Validator\ConstraintViolationList;
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

/**
 * Description of Registration
 *
 * @author Hristo
 */
class RegistrationService implements RegistrationServiceInterface
{
    const CURRENT_WEB_PAGE = '/registration';
    private const NEXT_WEB_PAGE_GROUP = '/confirmation';
    
    private ValidatorInterface $validator;
    
    private RegistrationForm $form;
    
    private ?ConstraintViolationList $formErrors;
    
    private UserRepositoryServiceInterface $userService;
    private PhoneRepositoryServiceInterface $phoneService;
    private TransactionRepositoryServiceInterface $transactionService;
    private PhoneConfirmationRepositoryServiceInterface $phoneConfirmationService;
    private ConfirmationCodeSmsInterface $confirmationCodeSms;
    
    private string $errors;
    
    private bool $isFinishedRegistration;
    
    private string $nextWebPage;
    
    private bool $isSuccess;


    public function __construct(
        RegistrationForm $registrationForm,
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
        $this->nextWebPage = self::CURRENT_WEB_PAGE;
        $this->errors = '';
        $this->isFinishedRegistration = false;
        $this->isSuccess = false;
    }
    
    
    public function createForm(string $requestBody): RegistrationForm
    {
        $parsedRequestBody = \json_decode($requestBody, true);
        $this->form->setEmail($parsedRequestBody['email']);
        $phoneNumberInput = (string)trim($parsedRequestBody['phoneNumber']);
        $phoneNumberInt = substr($phoneNumberInput, 0, 1) === '0'
            ? (int)Country::BG_PHONE_CODE.substr($phoneNumberInput, 1)
            : (int)$phoneNumberInput;
        $this->form->setPhoneNumber($phoneNumberInt);
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
        if ($this->isFinishedRegistration) {
            throw new \Exception('The registration is already made.');
        }
        
        $this->notSetFormException();
        if (!$this->isValidForm()) {
            return null;
        }
        
        $user = $this->getOrCreateByEmail();
        if (is_null($user)) {
            return null;
        }
        
        $phone = $this->getOrCreatePhone();
        if (is_null($phone)) {
            return null;
        }
        
        $transaction = $this->createTransaction($user, $phone);
        if (is_null($transaction)) {
            return null;
        }
        
        $phoneConfirmation = $this->createPhoneConfirmation($transaction);
        if (is_null($phoneConfirmation) || $phoneConfirmation->getId() < 1) {
            return null;
        }
        
        $phoneConfirmationSms = $this->confirmationCodeSms->sendConfirmationCodeMessage($phoneConfirmation->getId());
        if (is_null($phoneConfirmationSms) || $phoneConfirmationSms->getId() < 1) {
            $this->errors .= 'Confirmation code SMS is not sent.';
            return null;
        }
        
        $this->isFinishedRegistration = true;
        $this->isSuccess = true;
        $this->nextWebPage = self::NEXT_WEB_PAGE_GROUP.'/'.$transaction->getId();
        
        return $phoneConfirmation;
    }
    
    /**
     * Returns the errors when a new registration is not recorded into the database.
     * 
     * @return type
     */
    public function getErrors(): string
    {
        return $this->errors;
    }
    
    public function isSuccess(): string
    {
        return $this->isSuccess;
    }
    
    public function getNextWebPage(): string
    {
        if (!$this->isFinishedRegistration) {
            throw new \Exception('The registration is not finished.');
        }
        
        return $this->nextWebPage;
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
        $phone = $this->phoneService->getOrCreateByAssembledPhoneNumber($this->form->getPhoneNumber());
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
