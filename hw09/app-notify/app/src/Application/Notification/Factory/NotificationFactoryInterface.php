<?php

namespace App\Application\Notification\Factory;

use App\Application\Notification\Model\NotificationModelInterface;

interface NotificationFactoryInterface
{
    public function create(): NotificationModelInterface;
}