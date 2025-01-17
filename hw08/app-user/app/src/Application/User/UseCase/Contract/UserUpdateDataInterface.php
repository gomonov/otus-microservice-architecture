<?php

namespace App\Application\User\UseCase\Contract;

interface UserUpdateDataInterface
{
    public function getId(): int;

    public function getFirstName(): string;

    public function getLastName(): string;

    public function getEmail(): string;

    public function getPhone(): int;
}