<?php

namespace App\Application\Advertisement\UseCase\Contract;

interface AdvertisementCreateDataInterface
{
    public function getUserId(): int;

    public function getTitle(): string;

    public function getText(): string;

    public function getToken(): string;

    public function getEmail(): string;
}