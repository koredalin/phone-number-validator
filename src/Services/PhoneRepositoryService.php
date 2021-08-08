<?php

namespace App\Services;

use App\Services\Interfaces\PhoneRepositoryServiceInterface;
use App\Repositories\Interfaces\PhoneRepositoryInterface;
use App\Repositories\Interfaces\CountryRepositoryInterface;
use App\Common\Interfaces\DateTimeManagerInterface;
use App\Entities\Phone;
use App\Entities\Country;

final class PhoneRepositoryService implements PhoneRepositoryServiceInterface
{
    private $phoneRepository;
    private CountryRepositoryInterface $countryRepository;
    private DateTimeManagerInterface $dtManager;
    
    private string $anyError;
    
    public function __construct(
        PhoneRepositoryInterface $phoneRepository,
        CountryRepositoryInterface $countryRepository,
        DateTimeManagerInterface $dtManager
    ){
        $this->phoneRepository = $phoneRepository;
        $this->countryRepository = $countryRepository;
        $this->dtManager = $dtManager;
        $this->anyError = '';
    }
    
    public function findOneById(int $id): ?Phone
    {
        return $this->phoneRepository->findOneById($id);
    }
    
    public function findByOnePhoneCodeNumber(Country $country, int $phoneNumber): ?Phone
    {
        return $this->phoneRepository->findByOnePhoneCodeNumber($country, $phoneNumber);
    }
    
    public function make(Country $country, int $phoneNumber): Phone
    {
        $phone = $this->phoneRepository->new();
        $phone->setCountry($country);
        $phone->setPhoneNumber($phoneNumber);
        $phone->setCreatedAt($this->dtManager->now());
        $phone->setUpdatedAt($this->dtManager->now());
        
        return $this->save($phone);
    }
    
    private function save(Phone $phone): Phone
    {
        $this->phoneRepository->save($phone);
        
        return $phone;
    }
    
    public function getAnyError(): string
    {
        return $this->anyError;
    }
    
    public function getOrCreateByAssembledPhoneNumber(string $assembledPhoneNumber): ?Phone
    {
        $countryObj = $this->getCountryFromAssembledNumber($assembledPhoneNumber);
        if (is_null($countryObj)) {
            return null;
        }
        $phoneNumber = $this->getPhoneNumberFromAssembledNumberCountry($assembledPhoneNumber, $countryObj);
        $dbPhone = $this->findByOnePhoneCodeNumber($countryObj, $phoneNumber);
        if ($dbPhone instanceof Phone && $dbPhone->getId() > 0) {
            return $dbPhone;
        }
        
        $phoneObj = $this->make($countryObj, $phoneNumber);
        
        return $phoneObj;
    }
    
    private function getCountryFromAssembledNumber(string $assembledPhoneNumber): ?Country
    {
        $assembledPhoneNumberTrimmed = trim($assembledPhoneNumber);
        if (substr($assembledPhoneNumberTrimmed, 0, 1) === '0') {
            return $this->countryRepository->findOneByPhoneCode(Country::BG_PHONE_CODE);
        }
        
        foreach ($this->countryRepository->findAll() as $country) {
            if (strpos($assembledPhoneNumber, trim($country->getPhoneCode())) === 0) {
                return $country;
            }
        }
        
        $this->anyError = 'Unknown phone code (country).';
        
        return null;
    }
    
    private function getPhoneNumberFromAssembledNumberCountry(string $assembledPhoneNumber, Country $country): int
    {
        $assembledPhoneNumberTrimmed = trim($assembledPhoneNumber);
        if ($country->getPhoneCode() === Country::BG_PHONE_CODE) {
            $phoneNumber = (int)substr($assembledPhoneNumberTrimmed, 1);
            if (strlen($phoneNumber) !== 9) {
                throw new \Exception('Validations not made yet.');
            }
            return $phoneNumber;
        }
        
        return $this->strReplaceFirst($country->getPhoneCode(), '', $assembledPhoneNumberTrimmed);
    }
    
    // https://stackoverflow.com/a/1252705
    private function strReplaceFirst($from, $to, $content)
    {
        $from = '/'.preg_quote($from, '/').'/';

        return preg_replace($from, $to, $content, 1);
    }
}