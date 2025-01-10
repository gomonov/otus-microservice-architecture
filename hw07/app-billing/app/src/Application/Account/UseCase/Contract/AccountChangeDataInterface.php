<?php

namespace App\Application\Account\UseCase\Contract;

interface AccountChangeDataInterface
{
    public function getUserId(): int;

    public function getSum(): int;
}