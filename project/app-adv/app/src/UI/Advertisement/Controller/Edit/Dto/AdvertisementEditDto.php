<?php

namespace App\UI\Advertisement\Controller\Edit\Dto;

use App\Application\Advertisement\UseCase\Contract\AdvertisementEditDataInterface;
use App\UI\Service\Auth\ValueObject\AuthValueObject;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Type;

class AdvertisementEditDto implements AdvertisementEditDataInterface
{
    #[NotBlank(message: 'Поле не может быть пустым')]
    #[Type("string")]
    private ?string $title;

    #[NotBlank(message: 'Поле не может быть пустым')]
    #[Type("string")]
    private ?string $text;

    private int $userId;

    private int $id;

    public function __construct(AuthValueObject $authData, Request $request, int $advId)
    {
        $params = $request->toArray();

        $this->text   = $params['text'] ?? null;
        $this->title  = $params['title'] ?? null;
        $this->userId = $authData->getId();
        $this->id     = $advId;
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

    public function getId(): int
    {
        return $this->id;
    }
}