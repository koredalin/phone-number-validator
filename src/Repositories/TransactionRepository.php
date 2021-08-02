<?php

namespace App\Repositories;

use Doctrine\ORM\EntityManagerInterface;
use App\Repositories\Interfaces\TransactionRepositoryInterface;
use App\Entities\Transaction;
use App\Entities\User;
use App\Entities\Phone;

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
    
    private string $dbException;
    
    public function __construct(EntityManagerInterface $em, Transaction $newTransaction)
    {
        $this->em = $em;
        $this->objectRepository = $this->em->getRepository(Transaction::class);
        $this->newTransaction = $newTransaction;
        $this->dbException = '';
    }
    
    
    /**
     * Returns an empty instance of the entity class.
     */
    public function new(): Transaction
    {
        $serializedNewObj = \serialize($this->newTransaction);
        
        return \unserialize($serializedNewObj);
    }
    
    public function findOneById(int $id): Transaction
    {
        return $this->objectRepository->find($id);
    }
    
    public function save(Transaction $transaction): Transaction
    {
        try {
            $this->objectRepository->persist($transaction);
            $this->objectRepository->flush();
        } catch (\Exception $exception) {
            $this->dbException = $exception->getMessage();
        }
        
        return $transaction;
    }
    
    public function getDatabaseException(): string
    {
        return $this->dbException;
    }
}
