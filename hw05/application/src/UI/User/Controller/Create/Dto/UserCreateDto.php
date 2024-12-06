<?php

namespace App\UI\User\Controller\Create\Dto;

use App\Application\User\UseCase\Contract\UserCreateDataInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Type;

class UserCreateDto implements UserCreateDataInterface
{
    #[NotBlank(message: 'Поле не может быть пустым')]
    #[Type("string")]
    private ?string $username;

    #[NotBlank(message: 'Поле не может быть пустым')]
    #[Type("string")]
    private ?string $firstName;

    #[NotBlank(message: 'Поле не может быть пустым')]
    #[Type("string")]
    private ?string $lastName;

    #[NotBlank(message: 'Поле не может быть пустым')]
    #[Email(message: 'Поле должно быть корректным email')]
    private ?string $email;

    #[NotBlank(message: 'Поле не может быть пустым')]
    #[Type("int")]
    private ?int $phone;

    public function __construct(Request $request)
    {
        $params = $request->toArray();

        $this->username  = $params['username'] ?? null;
        $this->firstName = $params['firstName'] ?? null;
        $this->lastName  = $params['lastName'] ?? null;
        $this->email     = $params['email'] ?? null;
        $this->phone     = $params['phone'] ?? null;
    }

    public function getUsername(): string
    {
        return $this->username;
    }

    public function getFirstName(): string
    {
        return $this->firstName;
    }

    public function getLastName(): string
    {
        return $this->lastName;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function getPhone(): int
    {
        return $this->phone;
    }
}