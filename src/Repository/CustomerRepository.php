<?php

declare(strict_types=1);

namespace App\Repository;

use App\Storage\Storage;
use App\Model\Transaction;
use App\Model\User;
use App\Model\Customer;
use App\Enums\TransactionType;

class CustomerRepository  implements CustomerRepositoryInterface
{
    private array $transactions;
    private array $customers;
    private Storage $storage;
    private Customer $customer;

    public function __construct(Storage $storage, Customer $customer)
    {
        $this->storage = $storage;
        $this->customer = $customer;

        $this->transactions = $this->storage->loadAll(Transaction::getModelName());
        $this->customers = $this->storage->loadAll(User::getModelName());

        $customer->setCustomerBalance($this->calculateBalance($this->customer->getEmail()));
    }
    private function calculateBalance(string $email): float
    {
        $amount = 0.0;
        //echo $email;
        //echo "<pre>";var_dump($this->transactions);//exit();
        foreach ($this->transactions as $transaction) {
            if ($transaction['email'] == $email) {
                if ($transaction['transaction_type'] == TransactionType::DEPOSIT->value) {
                    $amount += (float)$transaction['amount'];
                } else {
                    $amount -= (float)$transaction['amount'];
                }
            }
        }
        return $amount;
    }
    public function transferMoney(string $recepientEmail, float $amount): bool
    {
        $recepientExist = false;
        $insufficientBalance = false;

        $recepientArray = $this->storage->loadWhere(Customer::getModelName(), array("email" => $recepientEmail));
        if($recepientArray){
            $recepient=$recepientArray[0];
        }else{
            $recepient=null;
        }
         //echo "<pre/>";print_r($recepient);exit();
        if ($recepient) {
            $recepientExist = true;
            //echo "<pre/>";print_r($recepient);exit();
            $recepientCustomer = new Customer($recepient['name'], $recepient['email'], $recepient['password']);
            $recepientCustomer->setCustomerBalance($this->calculateBalance($recepientEmail));
            if ($this->customer->getCustomerBalance() < $amount) {
                $insufficientBalance = true;
            } else {
                $recepientCustomer->setCustomerBalance($recepientCustomer->getCustomerBalance() + $amount);
                $aTransaction = new Transaction($recepientCustomer, TransactionType::DEPOSIT, $amount);
                $this->transactions[] = $aTransaction;
                $this->saveTransactions($aTransaction);

                $this->customer->setCustomerBalance($this->customer->getCustomerBalance() - $amount);
                $aTransaction = new Transaction($this->customer, TransactionType::WITHDRAW, $amount);
                $this->transactions[] = $aTransaction;
                $this->saveTransactions($aTransaction);
            }
        }
        //var_dump($this->customers);exit();


        if (!$recepientExist) {
            $_SESSION['message'] = "Recepient email not found";
        } else {
            if ($insufficientBalance) {
                $_SESSION['message'] = "Insufficient Balance";
            } else {
                $_SESSION['message'] = "Money Transfered!";
                return true;
            }
        }
        return false;
    }
    public function deposit(float $amount): bool
    {
        $aTransaction = new Transaction($this->customer, TransactionType::DEPOSIT, $amount);
        $success = $this->saveTransactions($aTransaction);
        if ($success) {
            $this->transactions[] = $aTransaction;
            $this->customer->setCustomerBalance($this->customer->getCustomerBalance() + $amount);
        }
        return $success;
    }

    public function withdraw(float $amount): bool
    {
        $aTransaction = new Transaction($this->customer, TransactionType::WITHDRAW, $amount);
        $success = $this->saveTransactions($aTransaction);
        if ($success) {
            $this->customer->setCustomerBalance($this->customer->getCustomerBalance() - $amount);
            $this->transactions[] = $aTransaction;
        }
        return $success;
    }

    public function viewTransactions(string $email): ?array
    {
        return $result = $this->storage->loadJoinWhere(
            User::getModelName(),
            Transaction::getModelName(),
            array(User::getModelName().'.email' => $email),//WHERE
            //array('email' => $email),
            array('email' => 'email'),//JOIN BETWEEN
            array(
                User::getModelName() => 'name',
                Transaction::getModelName() => 'email,transaction_type,amount,transaction_date'
            )
        );
    }

    public function accountBalance()
    {
        printf("---------------------------------\n");
        //return $this->customer->getCustomerBalance();
        printf("Your Account Balance: %s\n", $this->customer->getCustomerBalance());
        printf("---------------------------------\n\n");
    }

    public function saveTransactions(Transaction $transaction): bool
    {
        $param = $transaction->toArray();
        return $this->storage->save(Transaction::getModelName(), $param);
    }
}
