<?php

namespace App\UI\User\Controller\Login\Dto;

use App\Application\User\UseCase\Contract\UserLoginDataInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Type;

class UserLoginDto implements UserLoginDataInterface
{
    #[NotBlank(message: 'Поле не может быть пустым')]
    #[Type("string")]
    private ?string $username;

    #[NotBlank(message: 'Поле не может быть пустым')]
    #[Type("string")]
    private ?string $password;

    public function __construct(Request $request)
    {
        $params = $request->toArray();

        $this->username  = $params['username'] ?? null;
        $this->password = $params['password'] ?? null;
    }

    public function getUsername(): string
    {
        return $this->username;
    }

    public function getPassword(): string
    {
        return $this->password;
    }
}