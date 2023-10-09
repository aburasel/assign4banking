<?php

namespace App\Model;

use App\Constants\AppConstants;
use App\Enums\AccessLevel;

class User implements Model
{
    private string $name;
    private string $email;
    private string $password;
    protected AccessLevel $accessLevel;
    public function __construct(string $name, string $email, string $password)
    {
        $this->name = $name;
        $this->email = $email;
        $this->password = $password;
    }
    public function setName(string $name)
    {
        $this->name = $name;
    }
    public function getName(): string
    {
        return $this->name;
    }
    public function setEmail(string $email)
    {
        $this->email = $email;
    }
    public function getEmail(): string
    {
        return $this->email;
    }
    public function setPassword(string $password)
    {
        $this->password = $password;
    }
    public function getPassword(): string
    {
        return $this->password;
    }

    public function getAccessLevel(): AccessLevel
    {
        return $this->accessLevel;
    }
    // public function setAccessLevel(AccessLevel $accessLevel)
    // {
    //     return $this->accessLevel=$accessLevel;
    // }

    public static function getModelName(): string
    {
        return AppConstants::USER_STORAGE;
    }
    public function toArray(): array
    {
        return array(
            "name" => $this->getName(),
            "email" => $this->getEmail(),
            "password" => $this->getPassword(),
            "access_level" => $this->getAccessLevel()->value
        );
    }
}
//Single Responsibility Principle
//Open-Closed principle (open for extension closed for modification)
//Liskov Substitution Principle
//Interface segregation principle. Keeping Minimal number of function in interface
//Depndency Inversion principle

//SOLID

//Encapsulate what varies