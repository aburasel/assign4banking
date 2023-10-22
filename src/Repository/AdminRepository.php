<?php

declare(strict_types=1);

namespace App\Repository;

use App\Enums\AccessLevel;
use App\Model\Transaction;
use App\Model\User;
use App\Storage\Storage;

class AdminRepository  implements AdminRepositoryInterface
{
    private array $transactions;
    private array $users;
    private Storage $storage;
    private User $admin;

    public function __construct(Storage $storage, User $admin)
    {
        $this->storage = $storage;
        $this->admin = $admin;
        $this->transactions = $this->storage->loadAll(Transaction::getModelName());
        $this->users = $this->storage->loadAll(User::getModelName());
    }

    public function viewCustomers(): ?array
    {
        //echo "<pre>";print_r($this->customers);exit();
        $customers = array();
        foreach ($this->users as $customer) {
            if ($customer['access_level'] == AccessLevel::CUSTOMER->value) {
                $customers[] = $customer;
            }
        }
        return $customers;
    }

    public function viewTransactions(string $email = null): ?array
    {
        // $where=array();
        // if($email){
        $where = array(User::getModelName() . '.email' => $email);
        // }
        return $result = $this->storage->loadJoinWhere(
            User::getModelName(),
            Transaction::getModelName(),
            $where, //WHERE
            //array('email' => $email),
            array('email' => 'email'), //JOIN BETWEEN
            array(
                User::getModelName() => 'name',
                Transaction::getModelName() => 'email,transaction_type,amount,transaction_date'
            )
        );
        // //echo "<pre>";print_r($this->transactions);exit();
        // $result=array();
        // if ($email == null) {
        //     $result=$this->transactions;
        // } else {
        //     foreach ($this->transactions as $transaction) {
        //         if ($transaction['email'] == $email) {
        //             $result[]=$transaction;
        //         }
        //     }
        // }
        // return $result;

    }
}
