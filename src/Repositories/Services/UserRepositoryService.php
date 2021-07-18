<?php

namespace App\Repositories\Services;

use App\Repositories\Interfaces\UserRepositoryInterface;
use App\Common\Interfaces\DateTimeManagerInterface;
use App\Entities\User;
use App\Entities\Email;
use App\Entities\Phone;

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
    
    public function findOneByUserName(string $userName): User
    {
        return $this->userRepository->findOneByUserName($userName);
    }
    
    public function make(Email $email, Phone $phone): User
    {
        $user = $this->userRepository->new();
        $user->email = $email;
        $user->phone = $phone;
        $user->createdAt = $this->dtManager->now();
        $user->updatedAt = $this->dtManager->now();
        
        return $this->save($user);
    }
    
    public function save(User $user): void
    {
        $this->userRepository->save($user);
        // Dispatch some event on every update
    }
    
    public function all(): array
    {
        return $this->userRepository->all();
    }
}