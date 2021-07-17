<?php

namespace App\Users;

use Doctrine\DBAL\Query\QueryBuilder;

/**
 * Description of UserRepository
 *
 * @author Hristo
 */
class UserRepository
{
    private QueryBuilder $queryBuilder;
    
    public function __construct(QueryBuilder $queryBuilder)
    {
        $this->queryBuilder = $queryBuilder;
    }
    
    public function getAll(): array
    {
        $query = $this->queryBuilder
            ->select('id', 'user_name')
            ->from('users');
        
        return $query->execute()->fetchAll();
    }
    
    public function add($name): void
    {
        $query = $this->queryBuilder
            ->insert('users')
            ->setValue('user_name', '?')
            ->setParameter(0, $name);
        
        $query->execute();
    }
}
