<?php

namespace App\Application\Advertisement\Repository;

use App\Application\Advertisement\Model\AdvertisementModelInterface;

interface AdvertisementRepositoryInterface
{
    public function findByIdempotencyKey(string $idempotencyKey): ?AdvertisementModelInterface;

    public function findById(int $advId): ?AdvertisementModelInterface;

    public function findByModerKey(string $moderKey): ?AdvertisementModelInterface;

    public function findAllPublish(): array;

    public function findAllByUser(int $userId): array;
}