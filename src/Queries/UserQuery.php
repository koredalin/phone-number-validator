<?php

namespace App\Queries;

// Doctrine libs
use Doctrine\DBAL\Query\QueryBuilder;

use App\Queries\Interfaces\UserQueryInterface;
//use App\Entities\User;

/**
 * Description of UserQuery
 *
 * @author Hristo
 */
final class UserQuery implements UserQueryInterface
{
    /**
     * @var QueryBuilder
     */
    private QueryBuilder $qb;
    
    public function __construct(QueryBuilder $qb)
    {
        $this->qb = $qb;
    }
    
    
    public function all(): array
    {
        $query = $this->qb->select('*')->from('users')->orderBy('id');
        
//        print_r($result); exit;
        
        return $query->execute()->fetchAll();
    }
}
