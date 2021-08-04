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
    
    public function __construct(
        PhoneRepositoryInterface $phoneRepository,
        CountryRepositoryInterface $countryRepository,
        DateTimeManagerInterface $dtManager
    ){
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
        $phone->setCountry($country);
        $phone->setPhoneNumber($phoneNumber);
        $phone->setCreatedAt($this->dtManager->now());
        $phone->setUpdatedAt($this->dtManager->now());
        
        return $this->save($phone);
    }
    
    private function save(Phone $phone): Phone
    {
        $this->phoneRepository->save($phone);
        echo __LINE__; exit;
        
        return $phone;
    }
    
    public function getDatabaseException(): string
    {
        return $this->phoneRepository->getDatabaseException();
    }
    
    public function getOrCreateByAssembledPhoneNumber(string $assembledPhoneNumber): Phone
    {
        $countryObj = $this->getCountryFromAssembledNumber($assembledPhoneNumber);
        $phoneNumber = $this->getPhoneNumberFromAssembledNumberCountry($assembledPhoneNumber, $countryObj);
        
        $phoneObj = $this->make($countryObj, $phoneNumber);
        print_r($countryObj);
        echo $assembledPhoneNumber. ' |||||| '.$phoneNumber. ' ||||||||||||| ';
        print_r($phoneObj);
        exit;
        
        return $phoneObj;
    }
    
    private function getCountryFromAssembledNumber(string $assembledPhoneNumber): Country
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
        
        throw new \Exception('Unknown phone code - Country.');
    }
    
    private function getPhoneNumberFromAssembledNumberCountry(string $assembledPhoneNumber, Country $country): int
    {
        $assembledPhoneNumberTrimmed = trim($assembledPhoneNumber);
        if ($country->getPhoneCode() === Country::BG_PHONE_CODE) {
            $phoneNumber = (int)substr($assembledPhoneNumberTrimmed, 1);
            if (strlen($phoneNumber) !== 9) {
                throw new Exception('Validations not made yet.');
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