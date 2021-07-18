<?php

namespace App\Entities;

use DateTime;
use App\Entities\Phone;
use App\Entities\User;

/**
 * @Entity
 * @Table(name="transactions")
 */
class Transaction
{
    const STATUS_AWAITING_REQUEST = 'awaiting_request';
    const STATUS_CONFIRMED = 'confirmed';
    
    const ALL_STATUSES = [
        self::STATUS_AWAITING_REQUEST,
        self::STATUS_CONFIRMED,
    ];
    
    /** 
     * @Id
     * @Column(type="bigint")
     * @GeneratedValue
     */
    private int $id;
    
    /**
     * @Column(name="user_id")
     */
    private User $user;
    
    /**
     * @Column(name="phone_id", type="bigint")
     * 
     * @ManyToOne(targetEntity="Phone", inversedBy="users")
     * @JoinColumn(name="phone_id", nullable="false", referencedColumnName="id")
     */
    private Phone $phone;
    
    /**
     * @Column(length=30, name="status")
     */
    private string $status;
    
    /**
     * @Column(length=255)
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
    
    public function setUser(User $email): void
    {
        $this->user = $email;
    }
    
    public function setPhone(Phone $phone): void
    {
        $this->phone = $phone;
    }
    
    public function setStatus(string $status): void
    {
        if (in_array($status, self::ALL_STATUSES, true)) {
            throw new \InvalidArgumentException('Not supported phone confirmation status code: '.var_export($status, true));
        }
        
        $this->status = $status;
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
    
    public function getUser(): User
    {
        return $this->user;
    }
    
    public function getPhone(): Phone
    {
        return $this->phone;
    }
    
    public function getStatus(): string
    {
        return $this->status;
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
