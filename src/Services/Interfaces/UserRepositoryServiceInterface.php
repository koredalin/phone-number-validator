<?php

namespace App\Services\Interfaces;

use App\Entities\User;

/**
 *
 * @author Hristo
 */
interface UserRepositoryServiceInterface
{
    public function getOrCreateByEmail(string $email): User;
    
    public function findOneById(int $id): User;
    
    public function findOneByEmail(string $emailName): User;
    
    public function getDatabaseException(): string;
}
