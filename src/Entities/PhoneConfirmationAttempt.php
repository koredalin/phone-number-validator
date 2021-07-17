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
    const STATUS_AWAITING_RESPONSE = 1;
    const STATUS_CONFIRMED = 2;
    
    const ALL_STATUSES = [
        self::STATUS_AWAITING_RESPONSE,
        self::STATUS_CONFIRMED,
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
    
    /** @Column(name="status") */
    private int $status;
    
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
    
    public function setUserId(int $userId): void
    {
        $this->userId = $userId;
    }
    
    public function setPhoneConfirmation(int $phoneConfirmation): void
    {
        $this->phoneConfirmation = $phoneConfirmation;
    }
    
    public function setStatus(int $status): void
    {
        if (in_array($status, self::ALL_STATUSES)) {
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
    
    public function getStatus(): int
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
