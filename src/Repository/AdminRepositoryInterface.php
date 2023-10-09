<?php

namespace App\Repository;

interface AdminRepositoryInterface
{
    function viewTransactions(string $email = null): ?array;
    function viewCustomers(): ?array;
}
