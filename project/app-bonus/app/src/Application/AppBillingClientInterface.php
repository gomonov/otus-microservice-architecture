<?php

namespace App\Application;

interface AppBillingClientInterface
{
    public function exchange(int $sum, int $userId, string $idempotencyKey): bool;

    public function rollback(int $userId, string $idempotencyKey): bool;
}