<?php

namespace App\Repositories\Interfaces;

use App\Entities\User;

/**
 *
 * @author Hristo
 */
interface UserRepositoryInterface
{
    /**
     * Returns an empty instance of the entity class.
     */
    public function new(): User;
    
    public function findOneById(int $id): User;
    
    public function findOneByEmail(string $email): User;
    
    public function save(User $email): User;
    
    public function getDatabaseException(): string;
}
