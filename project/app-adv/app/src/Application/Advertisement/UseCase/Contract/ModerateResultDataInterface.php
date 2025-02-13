<?php

namespace App\Application\Advertisement\UseCase\Contract;

interface ModerateResultDataInterface
{
    public function getUuid(): string;

    public function getVerified(): bool;

    public function getReason(): string;
}