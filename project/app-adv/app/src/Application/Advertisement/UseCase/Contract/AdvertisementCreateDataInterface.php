<?php

namespace App\Application\Advertisement\UseCase\Contract;

interface AdvertisementCreateDataInterface
{
    public function getUserId(): int;

    public function getTitle(): string;

    public function getText(): string;

    public function getIdempotencyKey(): string;
}