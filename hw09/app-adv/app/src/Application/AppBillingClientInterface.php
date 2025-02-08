<?php

namespace App\Application;

interface AppBillingClientInterface
{
    public function pay(int $sum, int $userId, string $token, string $idempotencyKey): bool;

    public function rollback(int $userId, string $token, string $idempotencyKey): bool;
}