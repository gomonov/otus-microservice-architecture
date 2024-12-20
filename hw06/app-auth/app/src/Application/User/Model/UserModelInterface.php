<?php

namespace App\Application\User\Model;

interface UserModelInterface
{
    public function getId(): ?int;

    public function getUsername(): string;

    public function getPassword(): string;
}