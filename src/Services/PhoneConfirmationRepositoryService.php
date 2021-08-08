<?php

namespace App\Services;

use App\Services\Interfaces\PhoneConfirmationRepositoryServiceInterface;
use App\Repositories\Interfaces\PhoneConfirmationRepositoryInterface;
use App\Common\Interfaces\ConfirmationCodeGeneratorInterface;
use App\Common\Interfaces\DateTimeManagerInterface;
use App\Entities\PhoneConfirmation;
use App\Entities\Transaction;

final class PhoneConfirmationRepositoryService implements PhoneConfirmationRepositoryServiceInterface
{
    private PhoneConfirmationRepositoryInterface $phoneConfirmationRepository;
    private DateTimeManagerInterface $dtManager;
    private ConfirmationCodeGeneratorInterface $confirmationCodeGenerator;
    
    public function __construct(
        PhoneConfirmationRepositoryInterface $phoneConfirmationRepository,
        ConfirmationCodeGeneratorInterface $confirmationCodeGenerator,
        DateTimeManagerInterface $dtManager
    ){
        $this->phoneConfirmationRepository = $phoneConfirmationRepository;
        $this->confirmationCodeGenerator = $confirmationCodeGenerator;
        $this->dtManager = $dtManager;
    }
    
    public function findOneById(int $id): ?PhoneConfirmation
    {
        return $this->phoneConfirmationRepository->findOneById($id);
    }
    
    public function findLastByTransactionAwaitingStatus(Transaction $transaction): ?PhoneConfirmation
    {
        return $this->phoneConfirmationRepository->findLastByTransactionAwaitingStatus($transaction);
    }
    
    public function make(Transaction $transaction): PhoneConfirmation
    {
        $phoneConfirmationObj = $this->phoneConfirmationRepository->new();
        $phoneConfirmationObj->setTransaction($transaction);
        $phoneConfirmationObj->setConfirmationCode($this->confirmationCodeGenerator->generate());
        $phoneConfirmationObj->setStatus(PhoneConfirmation::STATUS_AWAITING_REQUEST);
        $phoneConfirmationObj->setCreatedAt($this->dtManager->now());
        $phoneConfirmationObj->setUpdatedAt($this->dtManager->now());
        
        return $this->save($phoneConfirmationObj);
    }
    
    public function getOrCreateByTransactionAwaitingStatus(Transaction $transaction): PhoneConfirmation
    {
        $dbObj = $this->findLastByTransactionAwaitingStatus($transaction);
        if (is_null($dbObj)) {
            return $this->make($transaction);
        }
        
        return $dbObj;
    }
    
    public function save(PhoneConfirmation $phoneConfirmation): PhoneConfirmation
    {
        $savedPhoneConfirmation = $this->phoneConfirmationRepository->save($phoneConfirmation);
        
        return $savedPhoneConfirmation;
    }
}