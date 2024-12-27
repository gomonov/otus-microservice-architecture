<?php

namespace App\UI\Account\Consumer;

use App\Application\Account\Exception\AccountException;
use App\Application\Account\UseCase\AccountCreateAction;
use App\Application\Kafka\ConsumerInterface;
use App\Application\Kafka\MessageInterface;
use JsonException;

readonly class AccountCreateConsumer implements ConsumerInterface
{
    public function __construct(private AccountCreateAction $accountCreateAction)
    {
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

        if (true === is_array($body)
            && array_key_exists('id', $body)
            && array_key_exists('action', $body)
            && $body['action'] === 'create'
        ) {
            try {
                $this->accountCreateAction->do((int)$body['id']);
            } catch (AccountException) {
            }
        }

        return true;
    }

    public function getTopic(): string
    {
        return 'user.event';
    }

    public function getGroup(): string
    {
        return 'account.create';
    }

    public function getTimeout(): int
    {
        return 3000;
    }

    public function getDescription(): string
    {
        return 'Создание счёта для нового пользователя';
    }
}