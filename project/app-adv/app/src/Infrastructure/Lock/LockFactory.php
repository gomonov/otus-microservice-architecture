<?php

namespace App\Infrastructure\Lock;

use App\Application\Lock\LockFactoryInterface;
use App\Application\Lock\LockInterface;
use Symfony\Component\Lock\LockFactory as BaseLockFactory;

readonly class LockFactory implements LockFactoryInterface
{
    public function __construct(
        private BaseLockFactory $lockFactory,
    ) {
    }

    public function create(string $lockKey, int $lockTtl = 10): LockInterface
    {
        return new Lock($this->lockFactory->createLock($lockKey, $lockTtl), $lockKey);
    }
}