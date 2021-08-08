<?php

namespace App\Repositories;

use Doctrine\ORM\EntityManagerInterface;
use App\Repositories\Interfaces\PhoneConfirmationAttemptRepositoryInterface;
use App\Entities\PhoneConfirmationAttempt;

/**
 * Description of PhoneConfirmationAttemptRepository
 *
 * @author Hristo
 */
final class PhoneConfirmationAttemptRepository implements PhoneConfirmationAttemptRepositoryInterface
{
    /**
     * @var EntityManagerInterface
     */
    private $em;
    
    /**
     * @var ObjectRepository
     */
    private $objectRepository;
    
    private PhoneConfirmationAttempt $newPhoneConfirmationAttempt;
    
    public function __construct(
        EntityManagerInterface $em,
        PhoneConfirmationAttempt $newPhoneConfirmationAttempt
    ) {
        $this->em = $em;
        $this->objectRepository = $this->em->getRepository(PhoneConfirmationAttempt::class);
        $this->newPhoneConfirmationAttempt = $newPhoneConfirmationAttempt;
    }
    
    
    /**
     * Returns an empty instance of the entity class.
     */
    public function new(): PhoneConfirmationAttempt
    {
        $serializedNewObj = \serialize($this->newPhoneConfirmationAttempt);
        
        return \unserialize($serializedNewObj);
    }
    
    public function findOneById(int $id): ?PhoneConfirmationAttempt
    {
        return $this->objectRepository->find($id);
    }
    
    public function save(PhoneConfirmationAttempt $phoneConfirmationAttempt): PhoneConfirmationAttempt
    {
        try {
            $this->em->persist($phoneConfirmationAttempt);
            $this->em->flush();
        } catch (\Exception $exception) {
            throw new \Exception($exception->getMessage());
        }
        
        return $phoneConfirmationAttempt;
    }
}
