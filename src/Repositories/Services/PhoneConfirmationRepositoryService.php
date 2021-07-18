<?php

namespace App\Repositories\Services;

use App\Repositories\Interfaces\PhoneConfirmationRepositoryInterface;
use App\Common\Interfaces\DateTimeManagerInterface;
use App\Entities\PhoneConfirmation;
use App\Entities\User;
use App\Entities\Phone;

final class PhoneConfirmationRepositoryService
{
    private $userRepository;
    private DateTimeManagerInterface $dtManager;
    
    public function __construct(PhoneConfirmationRepositoryInterface $userRepository, DateTimeManagerInterface $dtManager){
        $this->userRepository = $userRepository;
        $this->dtManager = $dtManager;
    }
    
    public function findOneById(int $id): PhoneConfirmation
    {
        return $this->userRepository->findOneById($id);
    }
    
    public function findOneByUserIdValidationCode(int $userId, int $validationCode): PhoneConfirmation
    {
        return $this->userRepository->findOneByPhoneConfirmationName($userId, $validationCode);
    }
    
    public function make(User $email, Phone $phone): PhoneConfirmation
    {
        $user = $this->userRepository->new();
        $user->email = $email;
        $user->phone = $phone;
        $user->createdAt = $this->dtManager->now();
        $user->updatedAt = $this->dtManager->now();
        
        return $this->save($user);
    }
    
    public function save(PhoneConfirmation $user): void
    {
        $this->userRepository->save($user);
        // Dispatch some event on every update
    }
    
    public function all(): array
    {
        return $this->userRepository->all();
    }
}