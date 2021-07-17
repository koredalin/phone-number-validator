<?php

namespace App\Repositories\Services;

use App\Repositories\Interfaces\UserRepositoryInterface;
use App\Entities\User;

final class UserRepositoryService
{
    private $userRepository;
    
    public function __construct(UserRepositoryInterface $userRepository){
        $this->userRepository = $userRepository;
    }
    
    public function findOneById(int $id): User
    {
        return $this->userRepository->findOneById($id);
    }
    
    public function findOneByUserName(string $userName): User
    {
        return $this->userRepository->findOneByUserName($userName);
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