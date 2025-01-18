<?php

namespace App\Application\Notification\UseCase;

use App\Application\Notification\Model\NotificationModelInterface;
use App\Application\Notification\Repository\NotificationRepositoryInterface;

readonly class NotificationGetAction
{
    public function __construct(
        private NotificationRepositoryInterface $notificationRepository,
    ) {
    }

    /**
     * @param int $userId
     * @return NotificationModelInterface[]
     */
    public function do(int $userId): array
    {
        return $this->notificationRepository->getAllByUserId($userId);
    }
}