<?php

namespace App\Queries\Services;

use App\Queries\Interfaces\UserQueryInterface;
use App\Entities\User;

final class UserQueryService
{
    private $userQuery;
    
    public function __construct(UserQueryInterface $userQuery){
        $this->userQuery = $userQuery;
    }
    
    public function findOneById(int $id): User
    {
        return $this->userQuery->findOneById($id);
    }
    
    public function findOneByUserName(string $userName): User
    {
        return $this->userQuery->findOneByUserName($userName);
    }
    
    public function updateProduct(Product $product): void
    {
        $this->userQuery->save($product);
        // Dispatch some event on every update
    }
    
    public function all(): array
    {
        return $this->userQuery->all();
    }
}