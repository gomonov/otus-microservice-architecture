<?php

namespace App\Application\Lock;

interface LockFactoryInterface
{
    public function create(string $lockKey, int $lockTtl = 10): LockInterface;
}