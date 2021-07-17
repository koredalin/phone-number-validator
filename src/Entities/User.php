<?php

namespace App\Entities;

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
    
    /** @Column(length=80, name="user_name") */
    private string $userName;
    
    public function setId(int $id): void
    {
        $this->id = $id;
    }
    
    public function setUserName(string $userName): void
    {
        $this->userName = $userName;
    }
    
    public function getId(): int
    {
        return $this->id;
    }
    
    public function getUserName(): string
    {
        return $this->userName;
    }
}
