<?php

namespace App\Application\Kafka;

use App\Application\Kafka\Exception\ProducerException;

interface ProducerInterface
{
    /**
     * @throws ProducerException
     */
    public function send(MessageInterface $message, string $topicName): void;
}