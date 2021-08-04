<?php

namespace App\Entities;

use DateTime;
use App\Entities\PhoneConfirmation;

/**
 * @Entity
 * @Table(name="phone_confirmation_attempts")
 */
class PhoneConfirmationAttempt
{
    const STATUS_CONFIRMED = 'confirmed';
    const STATUS_DENIED = 'denied';
    
    const ALL_STATUSES = [
        self::STATUS_CONFIRMED,
        self::STATUS_DENIED,
    ];
    
    /** 
     * @Id
     * @Column(type="bigint")
     * @GeneratedValue
     */
    private int $id;
    
    /**
     * @Column(name="phone_confirmation_id", type="bigint")
     * 
     * @ManyToOne(targetEntity="PhoneConfirmation")
     */
    private PhoneConfirmation $phoneConfirmation;
    
    /**
     * @Column(length=30, name="status")
     */
    private string $status;
    
    /**
     * @Column(name="created_at")
     */
    private \DateTime $createdAt;
        
    /**
     * @Column(name="updated_at")
     */
    private \DateTime $updatedAt;
    
    /**************************************************************************/
    /******************************* SETTERS **********************************/
    /**************************************************************************/
    
    public function setId(int $id): void
    {
        $this->id = $id;
    }
    
    public function setUserId(int $userId): void
    {
        $this->userId = $userId;
    }
    
    public function setPhoneConfirmation(int $phoneConfirmation): void
    {
        $this->phoneConfirmation = $phoneConfirmation;
    }
    
    public function setStatus(string $status): void
    {
        if (in_array($status, self::ALL_STATUSES, true)) {
            throw new \InvalidArgumentException('Not supported phone confirmation status code: '.var_export($status, true));
        }
        
        $this->status = $status;
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
    
    public function getPhoneConfirmation(): int
    {
        return $this->phoneConfirmation;
    }
    
    public function getStatus(): string
    {
        return $this->status;
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
