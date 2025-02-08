<?php

namespace App\Application\Bonus\UseCase\Contract;

interface BonusChangeDataInterface
{
    public function getUserId(): int;

    public function getSum(): int;
}