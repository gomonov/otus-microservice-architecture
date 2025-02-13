<?php

namespace App\Application;

interface AppBonusClientInterface
{
    public function add(int $sum, int $userId, string $idempotencyKey): bool;

    public function rollback(int $userId, string $idempotencyKey): bool;
}