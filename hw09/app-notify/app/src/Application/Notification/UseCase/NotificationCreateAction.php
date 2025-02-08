<?php

namespace App\Application\Notification\UseCase;

use App\Application\EntityStorage\EntityStorageServiceInterface;
use App\Application\EntityStorage\Exception\EntityStorageException;
use App\Application\Notification\Model\NotificationModelInterface;
use App\Application\Notification\UseCase\Contract\NotificationCreateDataInterface;
use App\Infrastructure\Factory\NotificationFactory;

readonly class NotificationCreateAction
{
    public function __construct(
        private NotificationFactory           $notificationFactory,
        private EntityStorageServiceInterface $entityStorageService,
    ) {
    }

    /**
     * @param NotificationCreateDataInterface $data
     * @return NotificationModelInterface
     * @throws EntityStorageException
     */
    public function do(NotificationCreateDataInterface $data): NotificationModelInterface
    {
        $notification = $this->notificationFactory->create();

        $notification->setUserId($data->getUserId());
        $notification->setEmail($data->getEmail());
        $notification->setText($data->getText());

        $this->entityStorageService->persist($notification);
        $this->entityStorageService->flush();

        return $notification;
    }
}