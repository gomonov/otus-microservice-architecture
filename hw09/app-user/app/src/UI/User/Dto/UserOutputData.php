<?php

namespace App\UI\User\Dto;

use App\Application\User\Model\UserModelInterface;
use JsonSerializable;

class UserOutputData implements JsonSerializable
{
    private int $id;

    private string $username;

    private string $firstName;

    private string $lastName;

    private string $email;

    private int $phone;

    public function __construct(UserModelInterface $user)
    {
        $this->id        = $user->getId();
        $this->username  = $user->getUsername();
        $this->firstName = $user->getFirstName();
        $this->lastName  = $user->getLastName();
        $this->email     = $user->getEmail();
        $this->phone     = $user->getPhone();
    }

    public function jsonSerialize(): array
    {
        return [
            'id'        => $this->id,
            'username'  => $this->username,
            'firstName' => $this->firstName,
            'lastName'  => $this->lastName,
            'email'     => $this->email,
            'phone'     => $this->phone,
        ];
    }
}