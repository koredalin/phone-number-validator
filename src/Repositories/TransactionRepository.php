<?php

namespace App\Repositories;

use Doctrine\ORM\EntityManagerInterface;
use App\Repositories\Interfaces\TransactionRepositoryInterface;
use App\Entities\Transaction;
use App\Entities\Email;
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
    
    private Transaction $newUser;
    
    public function __construct(EntityManagerInterface $em, Transaction $newUser)
    {
        $this->em = $em;
        $this->objectRepository = $this->em->getRepository(Transaction::class);
        $this->newUser = $newUser;
    }
    
    
    /**
     * Returns an empty instance of the entity class.
     */
    public function new(): Transaction
    {
        $serializedNewObj = \serialize($this->newUser);
        
        return \unserialize($serializedNewObj);
    }
    
    public function findOneById(int $id): Transaction
    {
        return $this->objectRepository->find($id);
    }
    
    public function findOneByEmailPhone(Email $email, Phone $phone): Transaction
    {
        return $this->objectRepository->findBy(['email_id' => $email->id, 'phone_id' => $phone->id]);
    }
    
    public function save(Transaction $transaction): void
    {
        $this->objectRepository->persist($transaction);
        $this->objectRepository->flush();
    }
}
