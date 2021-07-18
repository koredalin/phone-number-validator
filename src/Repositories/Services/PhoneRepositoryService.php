<?php

namespace App\Repositories\Services;

use App\Repositories\Interfaces\PhoneRepositoryInterface;
use App\Repositories\Interfaces\CountryRepositoryInterface;
use App\Common\Interfaces\DateTimeManagerInterface;
use App\Entities\Phone;

final class PhoneRepositoryService
{
    private const BG_PHONE_CODE = 359;
    
    private $phoneRepository;
    private CountryRepositoryInterface $countryRepository;
    private DateTimeManagerInterface $dtManager;
    
    public function __construct(PhoneRepositoryInterface $phoneRepository, CountryRepositoryInterface $countryRepository, DateTimeManagerInterface $dtManager){
        $this->phoneRepository = $phoneRepository;
        $this->countryRepository = $countryRepository;
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
    
    public function getOrCreateByAssembledPhoneNumber(string $assembledPhoneNumber): Phone
    {
        $assembledPhoneNumberTrimmed = trim($assembledPhoneNumber);
        $countryObj = null;
        $phoneNumber = null;
        if (substr($assembledPhoneNumberTrimmed, 0, 1) === '0') {
            $countryObj = $this->countryRepository->findOneByPhoneCode(self::BG_PHONE_CODE);
            $phoneNumber = (int)substr($assembledPhoneNumberTrimmed, 1);
            if (strlen($phoneNumber) !== 9) {
                throw new Exception('Validations not made yet.');
            }
        } else {
            foreach ($this->countryRepository->findAll() as $country) {
                if (strpos($assembledPhoneNumber, $country->phoneCode) === 0) {
                    $countryObj = $country;
                    break;
                }
            }
            $phoneNumber = $this->strReplaceFirst($countryObj->phoneCode, '', $assembledPhoneNumber);
        }
        if (is_null($countryObj) || strlen($phoneNumber) !== 9) {
            throw new Exception('Validations not made yet.');
        }
        
        $phoneObj = $this->make($countryObj, $phoneNumber);
        
        return $phoneObj;
    }
    
    // https://stackoverflow.com/a/1252705
    private function strReplaceFirst($from, $to, $content)
    {
        $from = '/'.preg_quote($from, '/').'/';

        return preg_replace($from, $to, $content, 1);
    }
}