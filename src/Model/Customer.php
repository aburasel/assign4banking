<?php
namespace App\Model;
use App\Enums\AccessLevel;
class Customer extends User
{
    private Transaction $tranaction;
    private float $balance = 0.0;
    public function __construct(string $name, string $email, string $password, $balance=0)
    {
        parent::__construct($name, $email, $password);
        $this->accessLevel = AccessLevel::CUSTOMER;
        $this->balance = $balance;
    }
    function setCustomerBalance(float $balance)  {
        $this->balance=$balance;
    }

    public function getCustomerBalance(): float  {
        return $this->balance;
    }
    
}
