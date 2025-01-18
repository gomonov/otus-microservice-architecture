<?php

namespace App\Application\Advertisement\Repository;

use App\Application\Advertisement\Model\AdvertisementModelInterface;

interface AdvertisementRepositoryInterface
{
    public function findByIdempotencyKey(string $idempotencyKey): ?AdvertisementModelInterface;
}