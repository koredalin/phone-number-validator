<?php

namespace App\Repositories;

use Doctrine\ORM\EntityManagerInterface;
use App\Repositories\Interfaces\TransactionRepositoryInterface;
use App\Entities\Transaction;

/**
 * Description of UserRepository
 *
 * @author Hristo
 */
final class TransactionRepository implements TransactionRepositoryInterface
{
    /**
     * @var EntityManagerInterface
     */
    private $em;
    
    /**
     * @var ObjectRepository
     */
    private $objectRepository;
    
    private Transaction $newTransaction;
    
    public function __construct(EntityManagerInterface $em, Transaction $newTransaction)
    {
        $this->em = $em;
        $this->objectRepository = $this->em->getRepository(Transaction::class);
        $this->newTransaction = $newTransaction;
    }
    
    
    /**
     * Returns an empty instance of the entity class.
     */
    public function new(): Transaction
    {
        $serializedNewObj = \serialize($this->newTransaction);
        
        return \unserialize($serializedNewObj);
    }
    
    public function findOneById(int $id): ?Transaction
    {
        $transaction = $this->objectRepository->find($id);
        
        return $transaction;
    }
    
    public function save(Transaction $transaction): Transaction
    {
        try {
            $this->em->persist($transaction);
            $this->em->flush();
        } catch (\Exception $exception) {
            throw new \Exception($exception->getMessage());
        }
        
        return $transaction;
    }
    
    public function getDatabaseException(): string
    {
        return $this->dbException;
    }
}
