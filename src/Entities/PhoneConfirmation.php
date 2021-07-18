<?php

namespace App\Entities;

use DateTime;
use App\Entities\Transaction;

/**
 * @Entity
 * @Table(name="phone_confirmations")
 */
class PhoneConfirmation
{
    const STATUS_AWAITING_RESPONSE = 'awaiting_response';
    const STATUS_CONFIRMED = 'confirmed';
    const STATUS_ABANDONED = 'abandoned';
    
    const ALL_STATUSES = [
        self::STATUS_AWAITING_RESPONSE,
        self::STATUS_CONFIRMED,
        self::STATUS_ABANDONED,
    ];
    
    /** 
     * @Id
     * @Column(type="bigint")
     * @GeneratedValue
     */
    private int $id;
    
    /**
     * @Column(name="user_id", type="bigint")
     * 
     * @ManyToOne(targetEntity="Transaction")
     */
    private Transaction $user;
    
    /**
     * @Column(name="validation_code")
     */
    private int $validationCode;
    
    /**
     * @Column(length=30, name="status")
     */
    private string $status;
    
    /**
     * @Column(name="confirmed_at")
     */
    private ?DateTime $confirmedAt;
    
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
    
    public function setUser(int $user): void
    {
        $this->user = $user;
    }
    
    public function setValidationCode(int $validationCode): void
    {
        $this->validationCode = $validationCode;
    }
    
    public function setStatus(string $status): void
    {
        if (in_array($status, self::ALL_STATUSES, true)) {
            throw new \InvalidArgumentException('Not supported phone confirmation status code: '.var_export($status, true));
        }
        
        $this->status = $status;
    }
    
    public function setConfirmedAt(?DateTime $confirmedAt): void
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
    
    public function getUser(): int
    {
        return $this->user;
    }
    
    public function getValidationCode(): int
    {
        return $this->validationCode;
    }
    
    public function getStatus(): string
    {
        return $this->status;
    }
    
    public function getConfirmedAt(): ?DateTime
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
