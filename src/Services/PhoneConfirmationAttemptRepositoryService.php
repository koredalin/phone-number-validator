<?php

namespace App\Services;

use App\Repositories\Interfaces\PhoneConfirmationAttemptRepositoryInterface;
use App\Common\Interfaces\DateTimeManagerInterface;
use App\Entities\PhoneConfirmation;
use App\Entities\PhoneConfirmationAttempt;

final class PhoneConfirmationAttemptRepositoryService
{
    private $phoneConfirmationAttemptRepository;
    private DateTimeManagerInterface $dtManager;
    
    public function __construct(
        PhoneConfirmationAttemptRepositoryInterface $phoneConfirmationAttemptRepository,
        DateTimeManagerInterface $dtManager
    ){
        $this->phoneConfirmationAttemptRepository = $phoneConfirmationAttemptRepository;
        $this->dtManager = $dtManager;
    }
    
    public function findOneById(int $id): PhoneConfirmationAttempt
    {
        return $this->phoneConfirmationAttemptRepository->findOneById($id);
    }
    
    public function createByPhoneConfirmationIsConfirmedCode(PhoneConfirmation $phoneConfirmation, bool $isConfirmedCode): PhoneConfirmationAttempt
    {
        $phoneConfirmationAttempt = $this->phoneConfirmationAttemptRepository->new();
        $phoneConfirmationAttempt->setPhoneConfirmation($phoneConfirmation);
        $isConfirmedCode
            ? $phoneConfirmationAttempt->setStatus(PhoneConfirmationAttempt::STATUS_CONFIRMED)
            : $phoneConfirmationAttempt->setStatus(PhoneConfirmationAttempt::STATUS_DENIED);
        $phoneConfirmationAttempt->setCreatedAt($this->dtManager->now());
        $phoneConfirmationAttempt->setUpdatedAt($this->dtManager->now());
        
        return $this->save($phoneConfirmationAttempt);
    }
    
    private function save(PhoneConfirmationAttempt $phoneConfirmationAttempt): PhoneConfirmationAttempt
    {
        $this->phoneConfirmationAttemptRepository->save($phoneConfirmationAttempt);
    }
    
    public function getDatabaseException(): string
    {
        return $this->phoneConfirmationAttemptRepository->getDatabaseException();
    }
}