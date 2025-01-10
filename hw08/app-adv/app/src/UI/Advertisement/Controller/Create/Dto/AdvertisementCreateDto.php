<?php

namespace App\UI\Advertisement\Controller\Create\Dto;

use App\Application\Advertisement\UseCase\Contract\AdvertisementCreateDataInterface;
use App\UI\Service\Auth\ValueObject\AuthValueObject;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Type;

class AdvertisementCreateDto implements AdvertisementCreateDataInterface
{
    #[NotBlank(message: 'Поле не может быть пустым')]
    #[Type("string")]
    private ?string $title;

    #[NotBlank(message: 'Поле не может быть пустым')]
    #[Type("string")]
    private ?string $text;

    private int $userId;

    private string $email;
    
    private string $token;

    public function __construct(AuthValueObject $authData, Request $request)
    {
        $params = $request->toArray();

        $this->text  = $params['text'] ?? null;
        $this->title  = $params['title'] ?? null;
        $this->userId = $authData->getId();
        $this->token  = $authData->getToken();
        $this->email  = $authData->getEmail();
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getText(): string
    {
        return $this->text;
    }

    public function getUserId(): int
    {
        return $this->userId;
    }

    public function getToken(): string
    {
        return $this->token;
    }

    public function getEmail(): string
    {
        return $this->email;
    }
}