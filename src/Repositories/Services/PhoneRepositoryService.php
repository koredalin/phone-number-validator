<?php

namespace App\Repositories\Services;

use App\Repositories\Interfaces\PhoneRepositoryInterface;
use App\Common\Interfaces\DateTimeManagerInterface;
use App\Entities\Phone;
use App\Entities\Country;

final class PhoneRepositoryService
{
    private $phoneRepository;
    private DateTimeManagerInterface $dtManager;
    
    public function __construct(PhoneRepositoryInterface $phoneRepository, DateTimeManagerInterface $dtManager){
        $this->phoneRepository = $phoneRepository;
        $this->dtManager = $dtManager;
    }
    
    public function findOneById(int $id): Phone
    {
        return $this->phoneRepository->findOneById($id);
    }
    
    public function findByOnePhoneCodeNumber(int $phoneCode, int $phoneNumber): Phone
    {
        return $this->phoneRepository->findByOnePhoneCodeNumber($phoneCode, $phoneNumber);
    }
    
    public function make(Country $country, int $phoneNumber): Phone
    {
        $phone = $this->phoneRepository->new();
        $phone->country = $country;
        $phone->phoneNumber = $phoneNumber;
        $phone->createdAt = $this->dtManager->now();
        $phone->updatedAt = $this->dtManager->now();
        
        return $this->save($phone);
    }
    
    public function save(Phone $phone): void
    {
        $this->phoneRepository->save($phone);
        // Dispatch some event on every update
    }
    
    public function all(): array
    {
        return $this->phoneRepository->all();
    }
}