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

    #[NotBlank(message: 'Заголовок X-Idempotency-Key не может быть пустым')]
    #[Type("string")]
    private ?string $idempotencyKey;

    private int $userId;

    public function __construct(AuthValueObject $authData, Request $request)
    {
        $params = $request->toArray();

        $this->text  = $params['text'] ?? null;
        $this->title  = $params['title'] ?? null;
        $this->userId = $authData->getId();

        $this->idempotencyKey = $request->headers->get('X-Idempotency-Key');
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

    public function getIdempotencyKey(): string
    {
        return $this->idempotencyKey;
    }
}