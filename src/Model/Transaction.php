<?php
namespace App\Model;

use App\Constants\AppConstants;
use App\Enums\TransactionType;

class Transaction implements Model
{
    protected float $amount;
    protected TransactionType $transaction_type;
    protected Customer $customer;
    public function __construct(Customer $customer, TransactionType $type, float $amount) {
        $this->customer = $customer;
        $this->transaction_type = $type;
        $this->amount = $amount;
    }
    public function getCustomer() : Customer {
        return $this->customer;
    }

    function getAmount() : float {
        return $this->amount;
    }
    function getTransactionType() : TransactionType {
        return $this->transaction_type;
    }

    public static function getModelName(): string{
        return AppConstants::TRANSACTION_STORAGE;
    }

    
    public function toArray(): array
    {
        return array(
            "email" => $this->getCustomer()->getEmail(),
            "transaction_type" => $this->getTransactionType()->value,
            "amount" => $this->getAmount()
        );
    }
}