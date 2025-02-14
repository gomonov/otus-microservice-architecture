<?php

namespace App\UI\Advertisement\Consumer;

use App\Application\Advertisement\UseCase\ModerateResultAction;
use App\Application\EntityStorage\EntityStorageServiceInterface;
use App\Application\EntityStorage\Exception\EntityStorageException;
use App\Application\Kafka\ConsumerInterface;
use App\Application\Kafka\Exception\ProducerException;
use App\Application\Kafka\MessageInterface;
use App\UI\Advertisement\Consumer\Dto\ModerateResultDto;
use JsonException;
use Symfony\Component\Validator\Validator\ValidatorInterface;

readonly class ModerationConsumer implements ConsumerInterface
{
    public function __construct(
        private string                        $topic,
        private string                        $group,
        private ValidatorInterface            $validator,
        private ModerateResultAction          $moderateResultAction,
        private EntityStorageServiceInterface $entityStorageService,
    ) {
    }

    /**
     * @param MessageInterface $message
     * @return bool
     * @throws JsonException
     * @throws EntityStorageException
     * @throws ProducerException
     */
    public function execute(MessageInterface $message): bool
    {
        $body = $message->getBody();
        $body = json_decode($body, true, 512, JSON_THROW_ON_ERROR);

        if (false === is_array($body)) {
            return false;
        }

        $input = new ModerateResultDto($body);

        $violationList = $this->validator->validate($input);
        if ($violationList->count() > 0) {
            return false;
        }

        $this->moderateResultAction->do($input);

        $this->entityStorageService->clear();

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