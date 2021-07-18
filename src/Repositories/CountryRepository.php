<?php

namespace App\Repositories;

use Doctrine\ORM\EntityManagerInterface;
use App\Repositories\Interfaces\CountryRepositoryInterface;
use App\Entities\Country;

/**
 * @author Hristo
 */
final class CountryRepository implements CountryRepositoryInterface
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
     * @var Country
     */
    private Country $newCountry;
    
    public function __construct(EntityManagerInterface $em, Country $newCountry)
    {
        $this->em = $em;
        $this->objectRepository = $this->em->getRepository(Country::class);
        $this->newCountry = $newCountry;
    }
    
    
    /**
     * Returns an empty instance of the entity class.
     */
    public function new(): Country
    {
        $serializedNewObj = \serialize($this->newCountry);
        
        return \unserialize($serializedNewObj);
    }
    
    public function findOneById(int $id): Country
    {
        return $this->objectRepository->find($id);
    }
    
    public function findByOneCountryName(string $countryName): Country
    {
        return $this->objectRepository->findBy(['country_name' => $countryName]);
    }
    
    public function save(Country $country): void
    {
        $this->objectRepository->persist($country);
        $this->objectRepository->flush();
    }
}
