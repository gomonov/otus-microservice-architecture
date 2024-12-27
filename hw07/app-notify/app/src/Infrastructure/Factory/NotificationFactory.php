<?php

namespace App\Infrastructure\Factory;

use App\Application\Notification\Factory\NotificationFactoryInterface;
use App\Application\Notification\Model\NotificationModelInterface;
use App\Infrastructure\Entity\Notification;

class NotificationFactory implements NotificationFactoryInterface
{
    public function create(): NotificationModelInterface
    {
        return new Notification();
    }
}