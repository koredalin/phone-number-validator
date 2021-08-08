<?php

namespace App\Repositories;

use Doctrine\ORM\EntityManagerInterface;
//use Doctrine\Persistence\ObjectRepository;
use Doctrine\Common\Collections\Collection as Collection;
use Doctrine\ORM\EntityRepository;
use App\Repositories\Interfaces\PhoneConfirmationAttemptRepositoryInterface;
use Doctrine\Common\Collections\Criteria;
use App\Entities\PhoneConfirmation;
use App\Entities\PhoneConfirmationAttempt;

/**
 * Description of PhoneConfirmationAttemptRepository
 *
 * @author Hristo
 */
final class PhoneConfirmationAttemptRepository implements PhoneConfirmationAttemptRepositoryInterface
{
    private EntityManagerInterface $em;
    
    private EntityRepository $objectRepository;
    
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
    
    public function findAllByPhoneConfirmationNoCoolDownDesc(PhoneConfirmation $phoneConfirmation): Collection
    {
        $criteria = new Criteria();
        $criteria->andWhere(Criteria::expr()->eq('phoneConfirmation', $phoneConfirmation))
            ->andWhere(Criteria::expr()->neq('status', PhoneConfirmationAttempt::STATUS_DENIED_COOL_DOWN))
            ->orderBy(['createdAt' => 'DESC']);
        $result = $this->objectRepository->matching($criteria);
        
        return $result;
    }
}
