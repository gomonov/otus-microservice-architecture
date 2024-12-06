<?php

namespace App\Application\User\UseCase\Contract;

interface UserCreateDataInterface
{
    public function getUsername(): string;

    public function getFirstName(): string;

    public function getLastName(): string;

    public function getEmail(): string;

    public function getPhone(): int;
}