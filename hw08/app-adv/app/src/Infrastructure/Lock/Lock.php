<?php

namespace App\Infrastructure\Lock;

use App\Application\Lock\LockException;
use App\Application\Lock\LockInterface;
use Random\RandomException;
use Symfony\Component\Lock\Exception\LockAcquiringException;
use Symfony\Component\Lock\Exception\LockConflictedException;
use Symfony\Component\Lock\Exception\LockReleasingException;
use Symfony\Component\Lock\LockInterface as SymfonyLockInterface;

readonly class Lock implements LockInterface
{
    public function __construct(private SymfonyLockInterface $lock, private string $lockKey)
    {
    }

    /**
     * @throws LockException
     */
    public function acquire(int $retryCount = 50, int $retrySleep = 100): void
    {
        $retry           = 0;
        $sleepRandomness = (int)($retrySleep / 10);
        $exception       = null;
        while (true) {
            if ($retry >= $retryCount) {
                break;
            }

            try {
                if ($this->lock->acquire()) {
                    return;
                }
            } catch (LockConflictedException|LockAcquiringException $e) {
                $exception = $e;
            }

            try {
                usleep(($retrySleep + random_int(-$sleepRandomness, $sleepRandomness)) * 1000);
            } catch (RandomException $e) {
                throw new LockException('Невозможно получить блокировку для ' . $this->lockKey, 0, $e);
            }

            $retry++;
        }

        throw new LockException('Невозможно получить блокировку для ' . $this->lockKey, 0, $exception);
    }

    /**
     * @throws LockException
     */
    public function refresh(float $ttl = null): void
    {
        try {
            $this->lock->refresh($ttl);
        } catch (LockConflictedException|LockAcquiringException) {
            throw new LockException('Невозможно продлить блокировку для ' . $this->lockKey);
        }
    }

    /**
     * @throws LockException
     */
    public function release(): void
    {
        try {
            # Отпускаем блокировку всегда
            $this->lock->release();
        } catch (LockReleasingException) {
            throw new LockException('Ошибка при отпуске блокировки ' . $this->lockKey);
        }
    }
}