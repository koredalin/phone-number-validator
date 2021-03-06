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
    const STATUS_AWAITING_REQUEST = 'awaiting_request';
    const STATUS_CONFIRMED = 'confirmed';
    const STATUS_ABANDONED = 'abandoned';
    
    const ALL_STATUSES = [
        self::STATUS_AWAITING_REQUEST,
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
     * @ManyToOne(targetEntity="Transaction")
     * @JoinColumn(name="transaction_id", nullable="false", referencedColumnName="id", onDelete="CASCADE")
     * @var Transaction
     */
    private Transaction $transaction;
    
    /**
     * @Column(name="confirmation_code")
     */
    private int $confirmationCode;
    
    /**
     * @Column(length=30, name="status")
     */
    private string $status;
    
    /**
     * @Column(name="confirmed_at", type="datetime", nullable="true")
     */
    private ?DateTime $confirmedAt;
    
    /**
     * @Column(name="confirmation_code_message", length=255)
     */
    private string $confirmationCodeMessage;
    
    /**
     * @Column(name="created_at", type="datetime")
     */
    private DateTime $createdAt;
        
    /**
     * @Column(name="updated_at", type="datetime")
     */
    private DateTime $updatedAt;
    
    /**************************************************************************/
    /******************************* SETTERS **********************************/
    /**************************************************************************/
    
    public function setId(int $id): void
    {
        $this->id = $id;
    }
    
    public function setTransaction(Transaction $transaction): void
    {
        $this->transaction = $transaction;
    }
    
    public function setConfirmationCode(int $confirmationCode): void
    {
        $this->confirmationCode = $confirmationCode;
    }
    
    public function setStatus(string $status): void
    {
        if (!in_array($status, self::ALL_STATUSES, true)) {
            throw new \InvalidArgumentException('Not supported phone confirmation status code: '.var_export($status, true));
        }
        
        $this->status = $status;
    }
    
    public function setConfirmedAt(?DateTime $confirmedAt): void
    {
        $this->confirmedAt = $confirmedAt;
    }
    
    public function setConfirmationCodeMessage(string $confirmationCodeMessage): void
    {
        $this->confirmationCodeMessage = $confirmationCodeMessage;
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
    
    public function getTransaction(): Transaction
    {
        return $this->transaction;
    }
    
    public function getConfirmationCode(): int
    {
        return $this->confirmationCode;
    }
    
    public function getStatus(): string
    {
        return $this->status;
    }
    
    public function getConfirmedAt(): ?DateTime
    {
        return $this->confirmedAt;
    }
    
    public function getConfirmationCodeMessage(): string
    {
        return $this->confirmationCodeMessage;
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
