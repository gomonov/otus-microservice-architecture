<?php

namespace App\Infrastructure\Kafka\Consumer;

use App\Application\Kafka\ConsumerInterface;
use InvalidArgumentException;
use LogicException;

class ConsumerRepository
{
    /**
     * @var ConsumerInterface[]
     */
    private array $consumers = [];

    public function __construct(iterable $consumers)
    {
        foreach ($consumers as $consumer) {
            if (array_key_exists($consumer->getGroup(), $this->consumers)) {
                throw new LogicException('There is already a consumer with such a group');
            }
            $this->consumers[$consumer->getGroup()] = $consumer;
        }
    }

    public function getConsumer(string $group): ConsumerInterface
    {
        if (!array_key_exists($group, $this->consumers)) {
            throw new InvalidArgumentException('Unknown consumer group "' . $group . '"');
        }
        return $this->consumers[$group];
    }

    /**
     * @return ConsumerInterface[]
     */
    public function getConsumers(): array
    {
        return $this->consumers;
    }
}