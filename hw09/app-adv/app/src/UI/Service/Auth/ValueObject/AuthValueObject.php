<?php

namespace App\UI\Service\Auth\ValueObject;

readonly class AuthValueObject
{
    public function __construct(private int $id, private string $username, private string $email, private string $token)
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

    public function getEmail(): string
    {
        return $this->email;
    }

    public function getToken(): string
    {
        return $this->token;
    }
}