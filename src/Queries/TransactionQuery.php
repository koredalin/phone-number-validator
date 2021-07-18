<?php

namespace App\Queries;

// Doctrine libs
use Doctrine\DBAL\Query\QueryBuilder;

use App\Queries\Interfaces\TransactionQueryInterface;
//use App\Entities\User;

/**
 * Description of UserQuery
 *
 * @author Hristo
 */
final class TransactionQuery implements TransactionQueryInterface
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
        $query = $this->qb->select('*')->from('transactions')->orderBy('id');
        
//        print_r($result); exit;
        
        return $query->execute()->fetchAll();
    }
}
