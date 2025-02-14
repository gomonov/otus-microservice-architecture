<?php

namespace App\Application\Advertisement\UseCase\Contract;

interface AdvertisementBaseDataInterface
{
    public function getId(): int;

    public function getUserId(): int;
}