<?php

namespace App\Application\Lock;

interface LockInterface
{
    /**
     * @throws LockException
     */
    public function acquire(int $retryCount, int $retrySleep): void;

    /**
     * @throws LockException
     */
    public function refresh(float $ttl = null): void;

    /**
     * @throws LockException
     */
    public function release(): void;
}