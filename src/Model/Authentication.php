<?php

namespace App\Model;

use App\Storage\Storage;
use App\Enums\AccessLevel;

class Authentication
{
    private array $registeredUsers;
    public function __construct(Storage $storage)
    {
        $this->storage = $storage;
        $this->registeredUsers = $this->storage->loadAll(User::getModelName());
    }
    protected User $user;
    protected Storage $storage;

    public function login(string $email, string $password, AccessLevel $accessLevel) //: ?User
    {
        //echo "<pre>";print_r($this->registeredUsers);exit();
        foreach ($this->registeredUsers as $reg) {
            if ($email == $reg['email'] && password_verify($password, $reg['password']) && $accessLevel->value == $reg['access_level']) {
                if ($accessLevel->value == AccessLevel::ADMIN->value) {
                    return new Admin($reg['name'], $reg['email'], $reg['password']);
                } else {
                    return new Customer($reg['name'], $reg['email'], $reg['password']);
                }
            }
        }

        return null;
    }

    public function register(User $user): ?User
    {
        $userExist = $this->isUserExist($user);
        //echo "<pre>";var_dump($userExist);exit();
        if ($userExist) {
            return null;
        } else {
            $this->registeredUsers[] = $user; // array push
            //$param=(array)$user;
            $param = array();
            $param["email"] = $user->getEmail();
            $param["name"] = $user->getName();
            $param["password"] = $user->getPassword();
            $param["access_level"] = $user->getAccessLevel()->value;
            //echo "<pre>";print_r($param);exit();
            $this->storage->save(User::getModelName(), $param);
            return $user;
        }
    }

    private function isUserExist(User $user): bool
    {
        $registeredUsers = $this->storage->loadAll(User::getModelName());

        //echo "<pre>";print_r($registeredUsers);exit();
        //echo "<pre>";print_r($user);exit();
        foreach ($registeredUsers as $reg) {
            if ($user->getEmail() == $reg["email"] && $user->getAccessLevel()->value == $reg["access_level"]) {
                return true;
            }
        }
        return false;
    }
}
