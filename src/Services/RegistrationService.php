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

/**
 * Description of Registration
 *
 * @author Hristo
 */
class RegistrationService implements RegistrationServiceInterface
{
    private ValidatorInterface $validator;
    
    private RegistrationForm $form;
    
    private ?ConstraintViolationList $formErrors;
    
    private UserRepositoryServiceInterface $userService;
    private PhoneRepositoryServiceInterface $phoneService;
    private TransactionRepositoryServiceInterface $transactionService;
    private PhoneConfirmationRepositoryServiceInterface $phoneConfirmationService;
    
    private string $dbErrors;
    
    public function __construct(
        UserRepositoryServiceInterface $userService,
        PhoneRepositoryServiceInterface $phoneService,
        TransactionRepositoryServiceInterface $transactionService,
        PhoneConfirmationRepositoryServiceInterface $phoneConfirmationService
    ) {
        $this->validator = Validation::createValidatorBuilder()->enableAnnotationMapping()->getValidator();
        $this->formErrors = null;
        $this->userService = $userService;
        $this->phoneService = $phoneService;
        $this->transactionService = $transactionService;
        $this->phoneConfirmationService = $phoneConfirmationService;
        $this->dbErrors = '';
    }
    
    
    public function createForm(string $requestBody): RegistrationForm
    {
        $parsedRequestBody = \json_decode($requestBody, true);
        $form = new RegistrationForm();
        $form->setEmail($parsedRequestBody['email']);
        $phoneNumberInput = (string)trim($parsedRequestBody['phoneNumber']);
        $phoneNumberInt = substr($phoneNumberInput, 0, 1) === '0'
            ? (int)Country::BG_PHONE_CODE.substr($phoneNumberInput, 1)
            : (int)$phoneNumberInput;
        $form->setPhoneNumber($phoneNumberInt);
        $form->setPassword($parsedRequestBody['password']);
        $this->form = $form;
        
        return $form;
    }
    
    public function isValidForm(): bool
    {
        $this->notSetFormException();
        
        $errors = $this->validator->validate($this->form);
        $this->formErrors = $errors;
        
        return count($errors);
    }
    
    public function getFormErrors(): string
    {
        $this->notSetFormException();
        
        return \json_encode($this->formErrors ?? '');
    }
    
    public function registrate(): ?PhoneConfirmation
    {
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
        if (is_null($phoneConfirmation)) {
            return null;
        }
        
        return $phoneConfirmation;
    }
    
    /**
     * Returns the errors when a new registration is not recorded into the database.
     * 
     * @return type
     */
    public function getDatabaseErrors(): string
    {
        return $this->dbErrors;
    }
    
    private function getOrCreateByEmail(): ?User
    {
        $user = $this->userService->getOrCreateByEmail($this->form->email);
        $exceptionEmail = $this->userService->getDatabaseException();
        if ($exceptionEmail !== '' || !isset($user->id) || $user->id < 1) {
            $this->dbErrors = $exceptionEmail;
            return null;
        }
        
        return $user;
    }
    
    private function getOrCreatePhone(): ?Phone
    {
        $phone = $this->phoneService->getOrCreateByAssembledPhoneNumber($this->form->phoneNumber);
        $exceptionPhone = $this->phoneService->getDatabaseException();
        if ($exceptionPhone !== '' || !isset($phone->id) || $phone->id < 1) {
            $this->dbErrors = $exceptionPhone;
            return null;
        }
    
        return $phone;
    }
    
    private function createTransaction(User $user, Phone $phone): ?Transaction
    {
        $transaction = $this->transactionService->make($user, $phone, $this->form->password);
        $exceptionTransaction = $this->transactionService->getDatabaseException();
        if ($exceptionTransaction !== '' || !isset($transaction->id) || $transaction->id < 1) {
            $this->dbErrors = $exceptionTransaction;
            return null;
        }
    
        return $transaction;
    }
    
    private function createPhoneConfirmation(Transaction $transaction): ?PhoneConfirmation
    {
        $phoneConfirmation = $this->phoneConfirmationService->getOrCreateByTransactionAwaitingStatus($transaction);
        $exceptionPhoneConfirmation = $this->phoneConfirmationService->getDatabaseException();
        if ($exceptionPhoneConfirmation !== '' || !isset($phoneConfirmation->id) || $phoneConfirmation->id < 1) {
            $this->dbErrors = $exceptionPhoneConfirmation;
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
