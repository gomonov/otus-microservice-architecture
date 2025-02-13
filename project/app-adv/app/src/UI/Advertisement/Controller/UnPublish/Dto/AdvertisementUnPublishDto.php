<?php

namespace App\UI\Advertisement\Controller\UnPublish\Dto;

use App\Application\Advertisement\UseCase\Contract\AdvertisementBaseDataInterface;
use App\UI\Service\Auth\ValueObject\AuthValueObject;

class AdvertisementUnPublishDto implements AdvertisementBaseDataInterface
{
    private int $userId;

    private int $id;

    public function __construct(AuthValueObject $authData, int $advId)
    {
        $this->userId = $authData->getId();
        $this->id     = $advId;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getUserId(): int
    {
        return $this->userId;
    }
}