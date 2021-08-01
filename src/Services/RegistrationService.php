<?php

namespace App\Services;

use App\Services\Interfaces\RegistrationInterface;
use App\Entities\Forms\RegistrationForm;
use Symfony\Component\Validator\ConstraintViolationList;
// Entities
use App\Entities\User;
use App\Entities\Phone;
use App\Entities\Transaction;
use App\Entities\PhoneConfirmation;
use App\Entities\PhoneConfirmationAttempt;
// Repository Services
use App\Services\UserRepositoryService;
use App\Services\PhoneRepositoryService;
use App\Services\TransactionRepositoryService;
use App\Services\PhoneConfirmationRepositoryService;
use App\Services\PhoneConfirmationAttemptRepositoryService;

/**
 * Description of Registration
 *
 * @author Hristo
 */
class RegistrationService implements RegistrationInterface
{
    private ValidatorInterface $validator;
    
    private RegistrationForm $form;
    
    private ?ConstraintViolationList $formErrors;
    
    private UserRepositoryService $userService;
    private PhoneRepositoryService $phoneService;
    private TransactionRepositoryService $transactionService;
    private PhoneConfirmationRepositoryService $phoneConfirmationService;
    private PhoneConfirmationAttemptRepositoryService $phoneConfirmationAttemptService;
    
    private $dbErrors;
    
    public function __construct(
        UserRepositoryService $userService,
        PhoneRepositoryService $phoneService,
        TransactionRepositoryService $transactionService,
        PhoneConfirmationRepositoryService $phoneConfirmationService,
        PhoneConfirmationAttemptRepositoryService $phoneConfirmationAttemptService
    ) {
        $this->validator = Validation::createValidatorBuilder()->enableAnnotationMapping()->getValidator();
        $this->formErrors = null;
        $this->userService = $userService;
        $this->phoneService = $phoneService;
        $this->transactionService = $transactionService;
        $this->phoneConfirmationService = $phoneConfirmationService;
        $this->phoneConfirmationAttemptService = $phoneConfirmationAttemptService;
        $this->dbErrors = null;
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
        if (!isset($this->form)) {
            throw new \Exception('Registration form not set yet. Use Registration::createForm() first.');
        }
        
        $errors = $this->validator->validate($this->form);
        $this->formErrors = $errors;
        
        return count($errors);
    }
    
    public function getFormErrors(): ?ConstraintViolationList
    {
        if (!isset($this->form)) {
            throw new \Exception('Registration form not set yet. Use Registration::createForm() first.');
        }
        
        return $this->formErrors;
    }
    
    public function registrate(): ?PhoneConfirmationAttempt
    {
        $email = $this->userService->getOrCreateByEmail($this->form->email);
        $exception = $this->userService->getDoctrineException();
        if ($exception !== '' || !isset($email->id) || $email->id < 1) {
            $this->dbErrors = $exception;
            return null;
        }
        
        
    }
    
    /**
     * Returns the errors when a new registration is not recorded into the database.
     * 
     * @return type
     */
    public function getDbErrors()
    {
        return $this->dbErrors;
    }
}
