<?php

namespace App\Repositories;

use Doctrine\ORM\EntityManagerInterface;
use App\Repositories\Interfaces\UserRepositoryInterface;
use App\Entities\User;

/**
 * Description of UserRepository
 *
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
    
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
        $this->objectRepository = $this->em->getRepository(User::class);
    }
    
    
    public function findOneById(int $id): User
    {
        return $this->objectRepository->find($id);
    }
    
    public function findByOneUserName(string $userName): User
    {
        return $this->objectRepository->findBy(['user_name' => $userName]);
    }
    
    public function save(User $user): void
    {
        $this->objectRepository->persist($user);
        $this->objectRepository->flush();
    }
}
