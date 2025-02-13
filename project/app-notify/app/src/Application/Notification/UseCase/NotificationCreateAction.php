<?php

namespace App\Application\Notification\UseCase;

use App\Application\AppUserClientInterface;
use App\Application\EntityStorage\EntityStorageServiceInterface;
use App\Application\EntityStorage\Exception\EntityStorageException;
use App\Application\Notification\Model\NotificationModelInterface;
use App\Application\Notification\UseCase\Contract\NotificationCreateDataInterface;
use App\Infrastructure\Factory\NotificationFactory;
use Exception;

readonly class NotificationCreateAction
{
    public function __construct(
        private NotificationFactory           $notificationFactory,
        private EntityStorageServiceInterface $entityStorageService,
        private AppUserClientInterface        $appUserClient,
    ) {
    }

    /**
     * @param NotificationCreateDataInterface $data
     * @throws EntityStorageException
     */
    public function do(NotificationCreateDataInterface $data): void
    {
        try {
            $email = $this->appUserClient->getEmail($data->getUserId());
        } catch (Exception) {
            return;
        }

        $notification = $this->notificationFactory->create();

        $notification->setUserId($data->getUserId());
        $notification->setText($data->getText());
        $notification->setEmail($email);


        $this->entityStorageService->persist($notification);
        $this->entityStorageService->flush();
    }
}