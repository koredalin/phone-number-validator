<?php

namespace App\Entities;

use DateTime;

/**
 * @Entity
 * @Table(name="phone_confirmations")
 */
class PhoneConfirmation
{
    const STATUS_AWAITING_RESPONSE = 1;
    const STATUS_CONFIRMED = 2;
    const STATUS_ABANDONED = 3;
    
    const ALL_STATUSES = [
        self::STATUS_AWAITING_RESPONSE,
        self::STATUS_CONFIRMED,
        self::STATUS_ABANDONED,
    ];
    
    /** 
     * @Id
     * @Column(type="integer")
     * @GeneratedValue
     */
    private int $id;
    
    /** @Column(name="user_id") */
    private int $userId;
    
    /** @Column(name="validation_code") */
    private int $validationCode;
    
    /** @Column(length=1, name="phone_code") */
    private int $status;
    
    /** @Column(name="confirmed_at") */
    private ?DateTime $confirmedAt;
    
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
    
    public function setValidationCode(int $validationCode): void
    {
        $this->validationCode = $validationCode;
    }
    
    public function setStatus(int $status): void
    {
        if (in_array($status, self::ALL_STATUSES)) {
            throw new \InvalidArgumentException('Not supported phone confirmation status code: '. var_export($status, true));
        }
        
        $this->status = $status;
    }
    
    public function setConfirmedAt(int $confirmedAt): void
    {
        $this->confirmedAt = $confirmedAt;
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
    
    public function getUserId(): int
    {
        return $this->userId;
    }
    
    public function getValidationCode(): int
    {
        return $this->validationCode;
    }
    
    public function getStatus(): int
    {
        return $this->status;
    }
    
    public function getConfirmedAt(): int
    {
        return $this->confirmedAt;
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
