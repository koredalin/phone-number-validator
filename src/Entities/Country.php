<?php

namespace App\Entities;

/**
 * @Entity
 * @Table(name="countries")
 */
class Country
{
    const BG_PHONE_CODE = 359;
    
    /** 
     * @Id
     * @Column(type="integer")
     * @GeneratedValue
     * 
     * @var int
     */
    private int $id;
    
    /**
     * @Column(length=100, name="country_name", unique="true")
     * 
     * @var string
     */
    private string $countryName;
    
    /**
     * @Column(length=3)
     * 
     * @var string
     */
    private string $iso3;
    
    /**
     * @Column(name="phone_code")
     * 
     * @var int
     */
    private int $phoneCode;
    
    /**************************************************************************/
    /******************************* SETTERS **********************************/
    /**************************************************************************/
    
    public function setId(int $id): void
    {
        $this->id = $id;
    }
    
    public function setCountryName(string $countryName): void
    {
        $this->countryName = $countryName;
    }
    
    public function setIso3(string $iso3): void
    {
        $this->iso3 = $iso3;
    }
    
    public function setPhoneCode(int $phoneCode): void
    {
        $this->phoneCode = $phoneCode;
    }
    
    /**************************************************************************/
    /******************************* GETTERS **********************************/
    /**************************************************************************/
    
    public function getId(): int
    {
        return $this->id;
    }
    
    public function getCountryName(): string
    {
        return $this->countryName;
    }
    
    public function getIso3(): string
    {
        return $this->iso3;
    }
    
    public function getPhoneCode(): int
    {
        return $this->phoneCode;
    }
    
    /**
     * 
     * @return string
     */
//    public function __toString()
//    {
//        return trim($this->id);
//    }
    
    /**************************************************************************/
    /**************************************************************************/
    /**************************************************************************/
}
