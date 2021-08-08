<?php

namespace App\Services;

use App\Services\Interfaces\UserRepositoryServiceInterface;
use App\Repositories\Interfaces\UserRepositoryInterface;
use App\Entities\User;
use App\Common\Interfaces\DateTimeManagerInterface;

final class UserRepositoryService implements UserRepositoryServiceInterface
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
        $user = $this->userRepository->new();
        $user->setEmail(trim($email));
        $user->setCreatedAt($this->dtManager->now());
        $user->setUpdatedAt($this->dtManager->now());
        
        return $this->save($user);
    }
    
    private function save(User $user): User
    {
        $savedUser = $this->userRepository->save($user);
        
        return $savedUser;
    }
    
    public function findOneById(int $id): ?User
    {
        return $this->userRepository->findOneById($id);
    }
    
    public function findOneByEmail(string $emailName): ?User
    {
        return $this->userRepository->findOneByEmail($emailName);
    }
}