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
    
    public function findOneById(int $id): Transaction
    {
        return $this->objectRepository->find($id);
    }
    
    public function findOneByEmailPhoneAwaitingStatus(User $email, Phone $phone): ?Transaction
    {
        return $this->objectRepository->findOneBy(['email_id' => $email->id, 'phone_id' => $phone->id, 'status' => Transaction::STATUS_AWAITING_REQUEST]);
    }
    
    public function save(Transaction $transaction): Transaction
    {
        $this->objectRepository->persist($transaction);
        $this->objectRepository->flush();
        
        return $transaction;
    }
}
