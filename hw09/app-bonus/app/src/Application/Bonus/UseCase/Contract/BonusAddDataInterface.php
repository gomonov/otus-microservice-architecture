<?php

namespace App\Application\Bonus\UseCase\Contract;

interface BonusAddDataInterface
{
    public function getUserId(): int;

    public function getSum(): int;

    public function getIdempotencyKey(): string;
}