<?php

namespace App\Repositories\Services;

use App\Repositories\Interfaces\PhoneConfirmationRepositoryInterface;
use App\Common\Interfaces\ConfirmationCodeGeneratorInterface;
use App\Common\Interfaces\DateTimeManagerInterface;
use App\Entities\PhoneConfirmation;
use App\Entities\Transaction;

final class PhoneConfirmationRepositoryService
{
    private $phoneConfirmationRepository;
    private DateTimeManagerInterface $dtManager;
    private ConfirmationCodeGeneratorInterface $confirmationCodeGenerator;
    
    public function __construct(PhoneConfirmationRepositoryInterface $phoneConfirmationRepository, ConfirmationCodeGeneratorInterface $confirmationCodeGenerator, DateTimeManagerInterface $dtManager){
        $this->phoneConfirmationRepository = $phoneConfirmationRepository;
        $this->confirmationCodeGenerator = $confirmationCodeGenerator;
        $this->dtManager = $dtManager;
    }
    
    public function findOneById(int $id): PhoneConfirmation
    {
        return $this->phoneConfirmationRepository->findOneById($id);
    }
    
    public function findOneByUserIdValidationCode(int $userId, int $validationCode): PhoneConfirmation
    {
        return $this->phoneConfirmationRepository->findOneByPhoneConfirmationName($userId, $validationCode);
    }
    
    public function make(Transaction $transaction): PhoneConfirmation
    {
        $phoneConfirmationObj = $this->phoneConfirmationRepository->new();
        $phoneConfirmationObj->transaction = $transaction;
        $phoneConfirmationObj->confirmationCode = $this->confirmationCodeGenerator->generate();
        $phoneConfirmationObj->status = PhoneConfirmation::STATUS_AWAITING_REQUEST;
        $phoneConfirmationObj->createdAt = $this->dtManager->now();
        $phoneConfirmationObj->updatedAt = $this->dtManager->now();
        
        return $this->save($phoneConfirmationObj);
    }
    
    public function save(PhoneConfirmation $user): void
    {
        $this->phoneConfirmationRepository->save($user);
        // Dispatch some event on every update
    }
    
    public function all(): array
    {
        return $this->phoneConfirmationRepository->all();
    }
}