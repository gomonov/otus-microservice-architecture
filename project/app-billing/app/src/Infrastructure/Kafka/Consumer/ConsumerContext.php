<?php

namespace App\Infrastructure\Kafka\Consumer;

use RdKafka\KafkaConsumer;

class ConsumerContext
{
    private bool           $isStop     = false;
    private string         $stopReason = '';
    private KafkaConsumer  $kafkaConsumer;
    private string         $group;
    private string         $topic;

    public function sigStop($signal): void
    {
        $this->stopReason = 'Received signal: ' . $signal;
        $this->isStop = true;
    }

    public function isStop(): bool
    {
        return $this->isStop;
    }

    public function getStopReason(): string
    {
        return $this->stopReason;
    }

    public function getKafkaConsumer(): KafkaConsumer
    {
        return $this->kafkaConsumer;
    }

    public function setKafkaConsumer(KafkaConsumer $kafkaConsumer): void
    {
        $this->kafkaConsumer = $kafkaConsumer;
    }

    public function getGroup(): string
    {
        return $this->group;
    }

    public function getTopic(): string
    {
        return $this->topic;
    }

    public function setGroup(string $group): void
    {
        $this->group = $group;
    }

    public function setTopic(string $topic): void
    {
        $this->topic = $topic;
    }
}