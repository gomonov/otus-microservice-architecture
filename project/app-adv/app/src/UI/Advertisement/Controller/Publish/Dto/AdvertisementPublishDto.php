<?php

namespace App\UI\Advertisement\Controller\Publish\Dto;

use App\Application\Advertisement\UseCase\Contract\AdvertisementPublishDataInterface;
use App\UI\Service\Auth\ValueObject\AuthValueObject;

class AdvertisementPublishDto implements AdvertisementPublishDataInterface
{
    private int $userId;

    private int $id;

    public function __construct(AuthValueObject $authData, int $advId)
    {
        $this->userId = $authData->getId();
        $this->id     = $advId;
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