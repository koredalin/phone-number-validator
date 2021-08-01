<?php

namespace App\Services;

use App\Repositories\Interfaces\UserRepositoryInterface;
use App\Entities\User;
use App\Common\Interfaces\DateTimeManagerInterface;

final class UserRepositoryService
{
    private $userRepository;
    private DateTimeManagerInterface $dtManager;
    
    public function __construct(UserRepositoryInterface $userRepository, DateTimeManagerInterface $dtManager){
        $this->userRepository = $userRepository;
        $this->dtManager = $dtManager;
    }
    
    public function findOneById(int $id): User
    {
        return $this->userRepository->findOneById($id);
    }
    
    public function findOneByEmail(string $emailName): User
    {
        return $this->userRepository->findOneByEmail($emailName);
    }
    
    public function make(string $email): User
    {
        $trimmedEmail = trim($email);
        $userObj = $this->userRepository->new();
        $userObj->email = $trimmedEmail;
        $userObj->createdAt = $this->dtManager->now();
        $userObj->updatedAt = $this->dtManager->now();
        
        return $this->save($userObj);
    }
    
    public function save(User $user): void
    {
        $user->email = trim($user->email);
        $this->userRepository->save($user);
        // Dispatch some event on every update
    }
    
    public function all(): array
    {
        return $this->userRepository->all();
    }
    
    public function getOrCreateByEmail(string $email): User
    {
        $trimmedEmail = trim($email);
        $emailObj = $this->findOneByEmail($trimmedEmail);
        if ($emailObj === null) {
            $emailObj = $this->make($trimmedEmail);
        }
        
        return $emailObj;
    }
}