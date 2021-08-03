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
}