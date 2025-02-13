<?php

namespace App\Application\Notification\UseCase\Contract;

interface NotificationCreateDataInterface
{
    public function getUserId(): int;

    public function getText(): string;
}