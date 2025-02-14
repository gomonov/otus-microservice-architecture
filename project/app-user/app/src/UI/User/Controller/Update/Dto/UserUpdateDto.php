<?php

namespace App\UI\User\Controller\Update\Dto;

use App\Application\User\UseCase\Contract\UserUpdateDataInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Type;

class UserUpdateDto implements UserUpdateDataInterface
{
    #[NotBlank(message: 'Поле не может быть пустым')]
    #[Type("int")]
    private ?int $id;

    #[Type("string")]
    private ?string $firstName;

    #[Type("string")]
    private ?string $lastName;

    #[Email(message: 'Поле должно быть корректным email')]
    private ?string $email;

    #[Type("int")]
    private ?int $phone;

    public function __construct(int $userId, Request $request)
    {
        $params = $request->toArray();

        $this->id        = $userId;
        $this->firstName = $params['firstName'] ?? null;
        $this->lastName  = $params['lastName'] ?? null;
        $this->email     = $params['email'] ?? null;
        $this->phone     = $params['phone'] ?? null;
    }

    public function getId(): int
    {
        return $this->id;
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