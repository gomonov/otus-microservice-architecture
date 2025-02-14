<?php

namespace App\Application\Moderation;

use App\Application\Kafka;
use App\Application\Kafka\Exception\ProducerException;
use App\Application\Kafka\MessageFabricInterface;
use App\Application\Kafka\ProducerInterface;
use App\Application\Redis\RedisInterface;
use JsonException;

readonly class ModerationService
{
    public function __construct(
        private int                    $advMinLength,
        private string                 $topic,
        private RedisInterface         $redis,
        private MessageFabricInterface $messageFabric,
        private ProducerInterface      $producer,
    ) {
    }

    /**
     * @param ModerationAdvDataInterface $data
     * @return void
     * @throws JsonException
     * @throws Kafka\Exception\ProducerException
     */
    public function moderate(ModerationAdvDataInterface $data): void
    {
        $resultRaw = $this->redis->get($data->getUuid());

        if (false !== $resultRaw) {
            /** @var ModerationResultData $result */
            $result = unserialize($resultRaw, ['allowed_classes' => ModerationResultData::class]);
            $this->answer($data->getUuid(), $result->isVerified(), $result->getReason());
            return;
        }

        if ($this->advMinLength >= mb_strlen($data->getText())) {
            $result = new ModerationResultData(false, 'Слишком короткое объявление');
        } else {
            $result = new ModerationResultData(true, '');
        }

        $this->redis->set($data->getUuid(), serialize($result), 24 * 60 * 60);
        $this->answer($data->getUuid(), $result->isVerified(), $result->getReason());
    }

    /**
     * @param string $uuid
     * @param bool   $result
     * @param string $reason
     * @return void
     * @throws JsonException
     * @throws ProducerException
     */
    private function answer(string $uuid, bool $result, string $reason): void
    {
        $message = $this->messageFabric->create();
        $message->setBody(json_encode(['uuid' => $uuid, 'verified' => $result, 'reason' => $reason], JSON_THROW_ON_ERROR));
        $this->producer->send($message, $this->topic);
    }
}