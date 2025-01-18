<?php

namespace App\UI\Notification\Consumer;

use App\Application\Kafka\ConsumerInterface;
use App\Application\Kafka\MessageInterface;
use App\Application\Notification\UseCase\NotificationCreateAction;
use App\UI\Notification\Consumer\Dto\NotificationCreateDto;
use JsonException;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class NotificationCreateConsumer implements ConsumerInterface
{
    public function __construct(
        private readonly NotificationCreateAction $notificationCreateAction,
        private readonly ValidatorInterface       $validator,
    ) {
    }

    /**
     * @param MessageInterface $message
     * @return bool
     * @throws JsonException
     */
    public function execute(MessageInterface $message): bool
    {
        $body = $message->getBody();
        $body = json_decode($body, true, 512, JSON_THROW_ON_ERROR);

        if (false === is_array($body)) {
            return false;
        }

        $input = new NotificationCreateDto($body);
        $violationList = $this->validator->validate($input);

        if ($violationList->count() > 0) {
            return false;
        }

        $this->notificationCreateAction->do($input);

        return true;
    }

    public function getTopic(): string
    {
        return 'order.event';
    }

    public function getGroup(): string
    {
        return 'notification.create';
    }

    public function getTimeout(): int
    {
        return 3000;
    }

    public function getDescription(): string
    {
        return 'Создание уведомления';
    }
}