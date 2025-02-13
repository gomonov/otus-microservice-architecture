<?php

namespace App\Application\Advertisement\UseCase\Contract;

interface AdvertisementEditDataInterface
{
    public function getUserId(): int;

    public function getTitle(): string;

    public function getText(): string;

    public function getId(): int;
}