<?php

namespace App\Repositories;

use Doctrine\ORM\EntityManagerInterface;
use App\Repositories\Interfaces\EmailRepositoryInterface;
use App\Entities\Email;

/**
 * @author Hristo
 */
final class EmailRepository implements EmailRepositoryInterface
{
    /**
     * @var EntityManagerInterface
     */
    private $em;
    
    /**
     * @var ObjectRepository
     */
    private $objectRepository;
    
    /**
     * @var Email
     */
    private Email $newEmail;
    
    public function __construct(EntityManagerInterface $em, User $newEmail)
    {
        $this->em = $em;
        $this->objectRepository = $this->em->getRepository(Email::class);
        $this->newEmail = $newEmail;
    }
    
    
    /**
     * Returns an empty instance of the entity class.
     */
    public function new(): Email
    {
        $serializedNewObj = \serialize($this->newEmail);
        
        return \unserialize($serializedNewObj);
    }
    
    public function findOneById(int $id): Email
    {
        return $this->objectRepository->find($id);
    }
    
    public function findByOneEmail(string $email): Email
    {
        return $this->objectRepository->findBy(['email' => $email]);
    }
    
    public function save(Email $user): void
    {
        $this->objectRepository->persist($user);
        $this->objectRepository->flush();
    }
}
