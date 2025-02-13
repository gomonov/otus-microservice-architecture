<?php

namespace App\UI\Bonus\Consumer;

use App\Application\Bonus\BonusException;
use App\Application\Bonus\UseCase\BonusCreateAction;
use App\Application\EntityStorage\EntityStorageServiceInterface;
use App\Application\Kafka\ConsumerInterface;
use App\Application\Kafka\MessageInterface;
use JsonException;

readonly class BonusCreateConsumer implements ConsumerInterface
{
    public function __construct(
        private string                        $userEventTopic,
        private string                        $bonusCreateGroup,
        private BonusCreateAction             $bonusCreateAction,
        private EntityStorageServiceInterface $entityStorageService,
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

        if (true === is_array($body)
            && array_key_exists('id', $body)
            && array_key_exists('action', $body)
            && $body['action'] === 'create'
        ) {
            try {
                $this->bonusCreateAction->do((int)$body['id']);
            } catch (BonusException) {
            }
        }

        $this->entityStorageService->clear();

        return true;
    }

    public function getTopic(): string
    {
        return $this->userEventTopic;
    }

    public function getGroup(): string
    {
        return $this->bonusCreateGroup;
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