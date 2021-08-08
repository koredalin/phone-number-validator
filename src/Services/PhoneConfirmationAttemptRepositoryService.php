<?php

namespace App\Services;

use App\Services\Interfaces\PhoneConfirmationAttemptRepositoryServiceInterface;
use App\Repositories\Interfaces\PhoneConfirmationAttemptRepositoryInterface;
use App\Common\Interfaces\DateTimeManagerInterface;
use App\Entities\PhoneConfirmation;
use App\Entities\PhoneConfirmationAttempt;

final class PhoneConfirmationAttemptRepositoryService implements PhoneConfirmationAttemptRepositoryServiceInterface
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
    
    public function findOneById(int $id): ?PhoneConfirmationAttempt
    {
        return $this->phoneConfirmationAttemptRepository->findOneById($id);
    }
    
    public function createByPhoneConfirmationInputConfirmationCode(PhoneConfirmation $phoneConfirmation, int $inputConfirmationCode): PhoneConfirmationAttempt
    {
        $caliberConfirmationCode = (int)$phoneConfirmation->getConfirmationCode();
        $isConfirmedCode = $inputConfirmationCode == $caliberConfirmationCode;
        
        $phoneConfirmationAttempt = $this->phoneConfirmationAttemptRepository->new();
        $phoneConfirmationAttempt->setPhoneConfirmation($phoneConfirmation);
        $phoneConfirmationAttempt->setInputConfirmationCode($inputConfirmationCode);
        $isConfirmedCode
            ? $phoneConfirmationAttempt->setStatus(PhoneConfirmationAttempt::STATUS_CONFIRMED)
            : $phoneConfirmationAttempt->setStatus(PhoneConfirmationAttempt::STATUS_DENIED);
        $phoneConfirmationAttempt->setCreatedAt($this->dtManager->now());
        $phoneConfirmationAttempt->setUpdatedAt($this->dtManager->now());
        
        return $this->save($phoneConfirmationAttempt);
    }
    
    private function save(PhoneConfirmationAttempt $phoneConfirmationAttempt): PhoneConfirmationAttempt
    {
        return $this->phoneConfirmationAttemptRepository->save($phoneConfirmationAttempt);
    }
}