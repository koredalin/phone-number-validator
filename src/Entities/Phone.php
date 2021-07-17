<?php

namespace App\Entities;

use DateTime;
use App\Entities\Country;

/**
 * @Entity
 * @Table(name="phones")
 */
class Phone
{
    /** 
     * @Id
     * @Column(type="bigint")
     * @GeneratedValue
     */
    private int $id;
    
    /**
     * @Column(name="country_id")
     * 
     * @ManyToOne(targetEntity="Country")
     */
    private Country $country;
    
    /** @Column(name="phone_number", type="bigint") */
    private int $phoneNumber;
    
    /** @Column(name="created_at") */
    private DateTime $createdAt;
        
    /** @Column(name="updated_at") */
    private DateTime $updatedAt;
    
    /**************************************************************************/
    /******************************* SETTERS **********************************/
    /**************************************************************************/
    
    public function setId(int $id): void
    {
        $this->id = $id;
    }
    
    public function setCountry(int $Country): void
    {
        $this->country = $Country;
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
    
    public function getCountry(): int
    {
        return $this->counrty;
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
