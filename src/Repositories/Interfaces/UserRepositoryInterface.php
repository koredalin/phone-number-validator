<?php

namespace App\Repositories\Interfaces;

use App\Entities\User;

/**
 *
 * @author Hristo
 */
interface UserRepositoryInterface
{
    public function findOneById(int $id): User;
    
    public function findByOneUserName(string $userName): User;
    
    public function save(User $user): void;
}
