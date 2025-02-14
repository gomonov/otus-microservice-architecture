<?php

namespace App\Application\Bonus\UseCase\Contract;

interface BonusRollbackDataInterface
{
    public function getUserId(): int;

    public function getIdempotencyKey(): string;
}