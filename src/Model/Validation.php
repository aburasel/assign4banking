<?php

namespace App\Model;

class Validation
{

    public function is_valid_email(string $email): bool
    {

        if ((strlen($email) == 0) || (!filter_var($email, FILTER_VALIDATE_EMAIL))) {
            $_SESSION['message'] = "Invalid email format";
            return false;
        }
        return true;
    }
    public function is_valid_name(string $name): bool
    {
        if ((strlen($name) == 0) || (!preg_match("/^[a-zA-Z-' ]*$/", $name))) {
            $_SESSION['message'] = "Invalid user name, use only letters and white space";
            return false;
        }

        return true;
    }
    public function is_valid_password(string $password): bool
    {
        if (strlen($password) == 0) {
            $_SESSION['message'] = "Password is invalid";
            return false;
        }

        return true;
    }
}
