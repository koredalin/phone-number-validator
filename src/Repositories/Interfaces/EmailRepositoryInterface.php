<?php

namespace App\Repositories\Interfaces;

use App\Entities\Email;

/**
 *
 * @author Hristo
 */
interface EmailRepositoryInterface
{
    /**
     * Returns an empty instance of the entity class.
     */
    public function new(): Email;
    
    public function findOneById(int $id): Email;
    
    public function findByOneEmail(string $email): Email;
    
    public function save(Email $email): void;
}
