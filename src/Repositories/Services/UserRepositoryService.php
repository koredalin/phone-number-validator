<?php

namespace App\Repositories\Services;

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
        return $this->userRepository->findOneByEmailName($emailName);
    }
    
    public function make(string $email): User
    {
        $emailObj = $this->userRepository->new();
        $emailObj->email = $email;
        $emailObj->createdAt = $this->dtManager->now();
        $emailObj->updatedAt = $this->dtManager->now();
        
        return $this->save($emailObj);
    }
    
    public function save(User $email): void
    {
        $this->userRepository->save($email);
        // Dispatch some event on every update
    }
    
    public function all(): array
    {
        return $this->userRepository->all();
    }
    
    public function getOrCreateByEmail(string $email): User
    {
        $emailObj = $this->findOneByEmail($email);
        if ($emailObj === null) {
            $emailObj = $this->make($email);
        }
        
        return $emailObj;
    }
}