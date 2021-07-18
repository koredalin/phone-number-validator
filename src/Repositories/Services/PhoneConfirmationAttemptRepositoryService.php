<?php

namespace App\Repositories\Services;

use App\Repositories\Interfaces\PhoneConfirmationAttemptRepositoryInterface;
use App\Common\Interfaces\DateTimeManagerInterface;
use App\Entities\PhoneConfirmationAttempt;
use App\Entities\Email;
use App\Entities\Phone;

final class PhoneConfirmationAttemptRepositoryService
{
    private $phoneConfirmationAttemptRepository;
    private DateTimeManagerInterface $dtManager;
    
    public function __construct(PhoneConfirmationAttemptRepositoryInterface $phoneConfirmationAttemptRepository, DateTimeManagerInterface $dtManager){
        $this->phoneConfirmationAttemptRepository = $phoneConfirmationAttemptRepository;
        $this->dtManager = $dtManager;
    }
    
    public function findOneById(int $id): PhoneConfirmationAttempt
    {
        return $this->phoneConfirmationAttemptRepository->findOneById($id);
    }
    
    public function findOneByPhoneConfirmationAttemptName(string $phoneConfirmationAttemptName): PhoneConfirmationAttempt
    {
        return $this->phoneConfirmationAttemptRepository->findOneByPhoneConfirmationAttemptName($phoneConfirmationAttemptName);
    }
    
    public function make(Email $email, Phone $phone): PhoneConfirmationAttempt
    {
        $phoneConfirmationAttempt = $this->phoneConfirmationAttemptRepository->new();
        $phoneConfirmationAttempt->email = $email;
        $phoneConfirmationAttempt->phone = $phone;
        $phoneConfirmationAttempt->createdAt = $this->dtManager->now();
        $phoneConfirmationAttempt->updatedAt = $this->dtManager->now();
        
        return $this->save($phoneConfirmationAttempt);
    }
    
    public function save(PhoneConfirmationAttempt $phoneConfirmationAttempt): void
    {
        $this->phoneConfirmationAttemptRepository->save($phoneConfirmationAttempt);
        // Dispatch some event on every update
    }
    
    public function all(): array
    {
        return $this->phoneConfirmationAttemptRepository->all();
    }
}