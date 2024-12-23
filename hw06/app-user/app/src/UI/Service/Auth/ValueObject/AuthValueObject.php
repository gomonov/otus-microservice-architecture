<?php

namespace App\UI\Service\Auth\ValueObject;

readonly class AuthValueObject
{
    public function __construct(private int $id, private string $username)
    {
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getUsername(): string
    {
        return $this->username;
    }
}