<?php

namespace App\UI\Service\Auth\ValueObject;

readonly class AuthValueObject
{
    public function __construct(private int $id)
    {
    }

    public function getId(): int
    {
        return $this->id;
    }
}