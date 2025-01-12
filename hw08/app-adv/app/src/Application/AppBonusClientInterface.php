<?php

namespace App\Application;

interface AppBonusClientInterface
{
    public function debit(int $sum, int $userId, string $token): bool;

    public function credit(int $sum, int $userId, string $token): bool;
}