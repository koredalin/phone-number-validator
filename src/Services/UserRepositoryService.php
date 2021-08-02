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
        $emailObj = $this->findOneByEmail($trimmedEmail);
        if ($emailObj === null) {
            $emailObj = $this->make($trimmedEmail);
        }
        
        return $emailObj;
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
    
    public function save(User $user): User
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
    
    public function getDoctrineException(): string
    {
        return $this->userRepository->getDatabaseException();
    }
    
//    public function all(): array
//    {
//        return $this->userRepository->all();
//    }
}