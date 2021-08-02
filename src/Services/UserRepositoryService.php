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
    
    public function getOrCreateByEmail(string $email): User
    {
        $trimmedEmail = trim($email);
        $user = $this->findOneByEmail($trimmedEmail);
        if ($user === null) {
            $user = $this->make($trimmedEmail);
        }
        
        return $user;
    }
    
    public function make(string $email): User
    {
        $trimmedEmail = trim($email);
        $user = $this->userRepository->new();
        $user->email = $trimmedEmail;
        $user->createdAt = $this->dtManager->now();
        $user->updatedAt = $this->dtManager->now();
        
        return $this->save($user);
    }
    
    private function save(User $user): User
    {
        $user->email = trim($user->email);
        $savedUser = $this->userRepository->save($user);
        
        return $savedUser;
    }
    
    public function findOneById(int $id): User
    {
        return $this->userRepository->findOneById($id);
    }
    
    public function findOneByEmail(string $emailName): User
    {
        return $this->userRepository->findOneByEmail($emailName);
    }
    
    public function getDatabaseException(): string
    {
        return $this->userRepository->getDatabaseException();
    }
}