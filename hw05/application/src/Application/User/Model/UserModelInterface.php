<?php

namespace App\Application\User\Model;

interface UserModelInterface
{
    public function getId(): ?int;

    public function getUsername(): string;

    public function setUsername(string $username): static;

    public function getFirstName(): string;

    public function setFirstName(string $firstName): static;

    public function getLastName(): string;

    public function setLastName(string $lastName): static;

    public function getEmail(): string;

    public function setEmail(string $email): static;

    public function getPhone(): int;

    public function setPhone(int $phone): static;
}