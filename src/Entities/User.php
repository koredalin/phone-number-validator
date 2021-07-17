<?php

namespace App\Entities;

use DateTime;

/**
 * @Entity
 * @Table(name="users")
 */
class User
{
    /** 
     * @Id
     * @Column(type="integer")
     * @GeneratedValue
     */
    private int $id;
    
    /** @Column(length=100, name="user_name") */
    private string $email;
    
    /** @Column(name="phone_number_id") */
    private int $phoneNumberId;
    
    /** @Column(length=30) */
    private string $password;
    
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
    
    public function setEmail(string $email): void
    {
        $this->email = $email;
    }
    
    public function setPhoneNumberId(int $phoneNumberId): void
    {
        $this->phoneNumberId = $phoneNumberId;
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
    
    public function getEmail(): string
    {
        return $this->email;
    }
    
    public function getPhoneNumberId(): int
    {
        return $this->phoneNumberId;
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
