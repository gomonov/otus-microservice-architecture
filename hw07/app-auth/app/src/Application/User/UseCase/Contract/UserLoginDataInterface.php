<?php

namespace App\Application\User\UseCase\Contract;

interface UserLoginDataInterface
{
    public function getUsername(): string;

    public function getPassword(): string;
}