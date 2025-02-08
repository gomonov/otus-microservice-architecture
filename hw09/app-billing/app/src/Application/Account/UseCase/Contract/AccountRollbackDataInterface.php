<?php

namespace App\Application\Account\UseCase\Contract;

interface AccountRollbackDataInterface
{
    public function getUserId(): int;

    public function getIdempotencyKey(): string;
}