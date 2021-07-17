<?php

namespace App\Entities;

/**
 * @Entity
 * @Table(name="phones")
 */
class Phone
{
    /** 
     * @Id
     * @Column(type="integer")
     * @GeneratedValue
     */
    private int $id;
    
    /** @Column(name="country_id") */
    private int $countryId;
    
    /** @Column(name="phone_number") */
    private int $phoneNumber;
    
    /** @Column(name="created_at") */
    private \DateTime $createdAt;
        
    /** @Column(name="updated_at") */
    private \DateTime $updatedAt;
    
    /**************************************************************************/
    /******************************* SETTERS **********************************/
    /**************************************************************************/
    
    public function setId(int $id): void
    {
        $this->id = $id;
    }
    
    public function setCountryId(int $CountryId): void
    {
        $this->countryId = $CountryId;
    }
    
    public function setPhoneNumber(int $phoneNumber): void
    {
        $this->phoneNumber = $phoneNumber;
    }
    
    public function setCreatedAt(DateTime $createdAt): void
    {
        $this->createdAt = $createdAt;
    }
    
    public function setUpdatedAt(DateTime $updatedAt): void
    {
        $this->updatedAt = $updatedAt;
    }
    
    /**************************************************************************/
    /******************************* GETTERS **********************************/
    /**************************************************************************/
    
    public function getId(): int
    {
        return $this->id;
    }
    
    public function getCountryId(): int
    {
        return $this->counrtyId;
    }
    
    public function getPhoneNumber(): int
    {
        return $this->phoneNumber;
    }
    
    public function getCreatedAt(): DateTime
    {
        return $this->createdAt;
    }
    
    public function getUpdatedAt(): DateTime
    {
        return $this->updatedAt;
    }
    
    /**************************************************************************/
    /**************************************************************************/
    /**************************************************************************/
}
