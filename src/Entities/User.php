<?php

namespace App\Entities;

use DateTime;
use App\Entities\Phone;
use App\Entities\Email;

/**
 * @Entity
 * @Table(name="users")
 */
class User
{
    /** 
     * @Id
     * @Column(type="bigint")
     * @GeneratedValue
     */
    private int $id;
    
    /**
     * @Column(name="email")
     */
    private Email $email;
    
    /**
     * @Column(name="phone_number_id", type="bigint")
     * 
     * @ManyToOne(targetEntity="Phone", inversedBy="users")
     * @JoinColumn(name="phone_id", nullable="false", referencedColumnName="id")
     */
    private Phone $phone;
    
    /**
     * @Column(length=30)
     */
    private string $password;
    
    /**
     * @Column(name="created_at")
     */
    private DateTime $createdAt;
        
    /**
     * @Column(name="updated_at")
     */
    private DateTime $updatedAt;
    
    /**************************************************************************/
    /******************************* SETTERS **********************************/
    /**************************************************************************/
    
    public function setId(int $id): void
    {
        $this->id = $id;
    }
    
    public function setEmail(Email $email): void
    {
        $this->email = $email;
    }
    
    public function setPhone(Phone $phone): void
    {
        $this->phone = $phone;
    }
    
    public function setPassword(string $password): void
    {
        $this->password = $password;
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
    
    public function getEmail(): Email
    {
        return $this->email;
    }
    
    public function getPhone(): Phone
    {
        return $this->phone;
    }
    
    public function getPassword(): string
    {
        return $this->password;
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
