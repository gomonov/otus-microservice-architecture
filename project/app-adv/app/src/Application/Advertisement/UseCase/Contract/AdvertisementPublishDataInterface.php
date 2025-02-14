<?php

namespace App\Application\Advertisement\UseCase\Contract;

interface AdvertisementPublishDataInterface
{
    public function getUserId(): int;

    public function getId();
}