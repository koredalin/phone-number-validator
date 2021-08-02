<?php

namespace App\Services\Interfaces;

use App\Entities\Country;

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
