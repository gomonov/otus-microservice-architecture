<?php

namespace App\Application;

interface AppBillingClientInterface
{
    public function pay(int $sum, int $userId, string $token): bool;
}