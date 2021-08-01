<?php

namespace App\Repositories;

use Doctrine\ORM\EntityManagerInterface;
use App\Repositories\Interfaces\UserRepositoryInterface;
use App\Entities\User;

/**
 * @author Hristo
 */
final class UserRepository implements UserRepositoryInterface
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
     * @var User
     */
    private User $newUser;
    
    private string $docrineException;
    
    public function __construct(EntityManagerInterface $em, User $newUser)
    {
        $this->em = $em;
        $this->objectRepository = $this->em->getRepository(User::class);
        $this->newUser = $newUser;
        $this->docrineException = '';
    }
    
    
    /**
     * Returns an empty instance of the entity class.
     */
    public function new(): User
    {
        $serializedNewObj = \serialize($this->newUser);
        
        return \unserialize($serializedNewObj);
    }
    
    public function findOneById(int $id): User
    {
        return $this->objectRepository->find($id);
    }
    
    public function findOneByEmail(string $email): User
    {
        return $this->objectRepository->findBy(['email' => $email]);
    }
    
    public function save(User $user): User
    {
        try {
            $this->objectRepository->persist($user);
            $this->objectRepository->flush();
        } catch (Exception $exception) {
            $this->docrineException = $exception->getMessage();
        }
        
        return $user;
    }
    
    public function getDoctrineException(): string
    {
        return $this->docrineException;
    }
}
