<?php

namespace App\UI\Moderation;

use App\Application\Kafka\ConsumerInterface;
use App\Application\Kafka\MessageInterface;
use App\Application\Moderation\ModerationService;
use JsonException;
use Symfony\Component\Validator\Validator\ValidatorInterface;

readonly class ModerationConsumer implements ConsumerInterface
{
    public function __construct(
        private string             $topic,
        private string             $group,
        private ModerationService  $moderationService,
        private ValidatorInterface $validator,
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

        $input = new ModerationAdvDto($body);

        $violationList = $this->validator->validate($input);
        if ($violationList->count() > 0) {
            return false;
        }

        $this->moderationService->moderate($input);

        return true;
    }

    public function getTopic(): string
    {
        return $this->topic;
    }

    public function getGroup(): string
    {
        return $this->group;
    }

    public function getTimeout(): int
    {
        return 3000;
    }

    public function getDescription(): string
    {
        return 'Модерация объявлений пользователя';
    }
}