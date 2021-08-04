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
        RegistrationForm $registrationForm,
        UserRepositoryServiceInterface $userService,
        PhoneRepositoryServiceInterface $phoneService,
        TransactionRepositoryServiceInterface $transactionService,
        PhoneConfirmationRepositoryServiceInterface $phoneConfirmationService
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
        $this->dbErrors = '';
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
        echo $this->getDatabaseErrors(); exit;
            return null;
        }
        echo __LINE__; exit;
//        $transaction = $this->createTransaction($user, $phone);
//        if (is_null($transaction)) {
//            return null;
//        }
//        
//        $phoneConfirmation = $this->createPhoneConfirmation($transaction);
//        if (is_null($phoneConfirmation)) {
//            return null;
//        }
        
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
        $user = $this->userService->getOrCreateByEmail($this->form->getEmail());
        $exceptionEmail = $this->userService->getDatabaseException();
        if ($exceptionEmail !== '' || $user->getId() < 1) {
            $this->dbErrors = $exceptionEmail;
            return null;
        }
        
        return $user;
    }
    
    private function getOrCreatePhone(): ?Phone
    {
        $phone = $this->phoneService->getOrCreateByAssembledPhoneNumber($this->form->getPhoneNumber());
        $phoneDbException = $this->phoneService->getDatabaseException();
        $phoneAnyError = $this->phoneService->getDatabaseException();
        if (is_null($phone) || $phoneDbException !== '' || $phoneAnyError !== '' || $phone->getId() < 1) {
            $this->dbErrors = $phoneDbException !== '' ? $phoneDbException : $phoneAnyError;
            return null;
        }
    
        return $phone;
    }
    
    private function createTransaction(User $user, Phone $phone): ?Transaction
    {
        $transaction = $this->transactionService->make($user, $phone, $this->form->getPassword());
        $exceptionTransaction = $this->transactionService->getDatabaseException();
        if ($exceptionTransaction !== '' || $transaction->getId() < 1) {
            $this->dbErrors = $exceptionTransaction;
            return null;
        }
    
        return $transaction;
    }
    
    private function createPhoneConfirmation(Transaction $transaction): ?PhoneConfirmation
    {
        $phoneConfirmation = $this->phoneConfirmationService->getOrCreateByTransactionAwaitingStatus($transaction);
        $exceptionPhoneConfirmation = $this->phoneConfirmationService->getDatabaseException();
        if ($exceptionPhoneConfirmation !== '' || $phoneConfirmation->getId() < 1) {
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
