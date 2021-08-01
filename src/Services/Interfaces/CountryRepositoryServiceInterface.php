<?php

namespace App\Services\Interfaces;

/**
 *
 * @author Hristo
 */
interface CountryRepositoryServiceInterface
{
    public function findOneById(int $id): Country;
    
    public function findOneByCountryName(string $countryName): Country;
    
    public function all(): array;
}
