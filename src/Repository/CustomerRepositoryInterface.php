<?php
namespace App\Repository;
interface CustomerRepositoryInterface
{
    function transferMoney(String $recepientEmail, float $amount):bool;
    function deposit(float $amount):bool;
    function withdraw(float $amount):bool;
    function viewTransactions(string $email):?array;
    function accountBalance();
}