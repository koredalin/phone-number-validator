<?php

namespace App\Repositories\Interfaces;

use App\Entities\Country;
use Doctrine\Common\Collections\Collection;

/**
 *
 * @author Hristo
 */
interface CountryRepositoryInterface
{
    /**
     * Returns an empty instance of the entity class.
     */
    public function new(): Country;
    
    public function findOneById(int $id): Country;
    
    public function findByOneCountryName(string $countryName): Country;
    
    public function findOneByPhoneCode(int $phoneCode): ?Country;
    
    public function findAll(): Collection;
    
    public function findAllOrderByPhoneCodeDesc(): Collection;
    
    public function save(Country $email): void;
}