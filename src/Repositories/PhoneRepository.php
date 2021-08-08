<?php

namespace App\Repositories;

use Doctrine\ORM\EntityManagerInterface;
use App\Repositories\Interfaces\PhoneRepositoryInterface;
use App\Entities\Phone;
use App\Entities\Country;

/**
 * Description of PhoneRepository
 *
 * @author Hristo
 */
final class PhoneRepository implements PhoneRepositoryInterface
{
    /**
     * @var EntityManagerInterface
     */
    private $em;
    
    /**
     * @var ObjectRepository
     */
    private $objectRepository;
    
    private Phone $newPhone;
    
    public function __construct(EntityManagerInterface $em, Phone $newPhone)
    {
        $this->em = $em;
        $this->objectRepository = $this->em->getRepository(Phone::class);
        $this->newPhone = $newPhone;
    }
    
    
    /**
     * Returns an empty instance of the entity class.
     */
    public function new(): Phone
    {
        $serializedNewObj = \serialize($this->newPhone);
        
        return \unserialize($serializedNewObj);
    }
    
    public function findOneById(int $id): ?Phone
    {
        return $this->objectRepository->find($id);
    }
    
    public function findByOnePhoneCodeNumber(Country $country, int $phoneNumber): ?Phone
    {
        return $this->objectRepository->findOneBy(['country' => $country, 'phoneNumber' => $phoneNumber]);
    }
    
    public function save(Phone $phone): Phone
    {
        try {
            $this->em->persist($phone);
            $this->em->flush();
        } catch (\Exception $exception) {
            throw new \Exception($exception->getMessage());
        }
        
        return $phone;
    }
}
