<?php
namespace App\Model;

use App\Enums\AccessLevel;

class Admin extends User
{
    public function __construct(string $name, string $email, string $password)
    {
        parent::__construct($name, $email, $password);
        $this->accessLevel = AccessLevel::ADMIN;
    }
}