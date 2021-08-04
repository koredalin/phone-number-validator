<?php

namespace App\Entities;

use DateTime;
use App\Entities\Country;
use Doctrine\ORM\Mapping\UniqueConstraint;
//use Doctrine\Common\Collections\ArrayCollection;
//use Doctrine\Common\Collections\Collection;

/**
 * @Entity
 * @Table(name="phones",
 *      uniqueConstraints={
 *          @UniqueConstraint(name="unique_phone",
 *              columns={"country_id", "phone_number"}
 *          )
 *      }
 * )
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
     * @ManyToOne(targetEntity="Country")
     * @JoinColumn(name="country_id", referencedColumnName="id")
     * @var Country
     */
    private Country $country;
    
    /**
     * @Column(name="phone_number", type="bigint")
     */
    private int $phoneNumber;
    
    /**
     * @Column(name="created_at", type="datetime")
     */
    private DateTime $createdAt;
        
    /**
     * @Column(name="updated_at", type="datetime")
     */
    private DateTime $updatedAt;
    
    /**
     * @OneToMany(targetEntity="User", mappedBy="phone")
     */
//    private ArrayCollection $users;
    
//    public function __construct(Country $country)
//    {
//        $this->country = $country;
//        $this->users = new ArrayCollection();
//    }
    
    /**************************************************************************/
    /******************************* SETTERS **********************************/
    /**************************************************************************/
    
    public function setId(int $id): void
    {
        $this->id = $id;
    }
    
    public function setCountry(Country $Country): void
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
    
//    public function addUser(User $user): void
//    {
//        $this->users->add($user);
//    }
//    
//    public function removeUser(User $user): void
//    {
//        $this->users->removeElement($user);
//    }
    
    /**************************************************************************/
    /******************************* GETTERS **********************************/
    /**************************************************************************/
    
    public function getId(): int
    {
        return $this->id;
    }
    
    public function getCountry(): Country
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
    
//    public function getUsers(): ArrayCollection
//    {
//        return $this->users;
//    }

    /**************************************************************************/
    /**************************************************************************/
    /**************************************************************************/
}
