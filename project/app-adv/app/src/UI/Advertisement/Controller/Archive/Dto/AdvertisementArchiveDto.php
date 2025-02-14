<?php

namespace App\UI\Advertisement\Controller\Archive\Dto;

use App\Application\Advertisement\UseCase\Contract\AdvertisementBaseDataInterface;
use App\UI\Service\Auth\ValueObject\AuthValueObject;

class AdvertisementArchiveDto implements AdvertisementBaseDataInterface
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