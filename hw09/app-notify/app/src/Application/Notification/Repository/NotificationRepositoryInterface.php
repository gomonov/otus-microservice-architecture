<?php

namespace App\Application\Notification\Repository;

use App\Application\Notification\Model\NotificationModelInterface;

interface NotificationRepositoryInterface
{
    /**
     * @param int  $userId
     * @param bool $lock
     * @return NotificationModelInterface[]
     */
    public function getAllByUserId(int $userId, bool $lock = false): array;
}