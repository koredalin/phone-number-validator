<?php

namespace App\Services;

use App\Services\Interfaces\CountryRepositoryServiceInterface;
use App\Repositories\Interfaces\CountryRepositoryInterface;
use App\Common\Interfaces\DateTimeManagerInterface;
use App\Entities\Country;

final class CountryRepositoryService implements CountryRepositoryServiceInterface
{
    private $countryRepository;
    private DateTimeManagerInterface $dtManager;
    
    public function __construct(CountryRepositoryInterface $countryRepository, DateTimeManagerInterface $dtManager){
        $this->countryRepository = $countryRepository;
        $this->dtManager = $dtManager;
    }
    
    public function findOneById(int $id): Country
    {
        return $this->countryRepository->findOneById($id);
    }
    
    public function findOneByCountryName(string $countryName): Country
    {
        return $this->countryRepository->findOneByCountryName($countryName);
    }
    
    public function all(): array
    {
        return $this->countryRepository->findAll();
    }
    
//    public function make(string $country, string $iso3, int $phoneCode): Country
//    {
//        $countryObj = $this->countryRepository->new();
//        $countryObj->country = $country;
//        $countryObj->iso3 = $iso3;
//        $countryObj->phoneCode = $phoneCode;
//        
//        return $this->save($countryObj);
//    }
    
//    public function save(Country $country): void
//    {
//        $this->countryRepository->save($country);
//        // Dispatch some event on every update
//    }
}