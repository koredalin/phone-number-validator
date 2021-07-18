<?php

namespace App\Repositories\Services;

use App\Repositories\Interfaces\EmailRepositoryInterface;
use App\Entities\Email;
use App\Common\Interfaces\DateTimeManagerInterface;

final class EmailRepositoryService
{
    private $emailRepository;
    private DateTimeManagerInterface $dtManager;
    
    public function __construct(EmailRepositoryInterface $emailRepository, DateTimeManagerInterface $dtManager){
        $this->emailRepository = $emailRepository;
        $this->dtManager = $dtManager;
    }
    
    public function findOneById(int $id): Email
    {
        return $this->emailRepository->findOneById($id);
    }
    
    public function findOneByEmailName(string $emailName): Email
    {
        return $this->emailRepository->findOneByEmailName($emailName);
    }
    
    public function make(string $email): Email
    {
        $emailObj = $this->userRepository->new();
        $emailObj->email = $email;
        $emailObj->createdAt = $this->dtManager->now();
        $emailObj->updatedAt = $this->dtManager->now();
        
        return $this->save($emailObj);
    }
    
    public function save(Email $email): void
    {
        $this->emailRepository->save($email);
        // Dispatch some event on every update
    }
    
    public function all(): array
    {
        return $this->emailRepository->all();
    }
}