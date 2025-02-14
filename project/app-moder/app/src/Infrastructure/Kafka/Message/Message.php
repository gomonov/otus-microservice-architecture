<?php

namespace App\Infrastructure\Kafka\Message;

use App\Application\Kafka\MessageInterface;
use DateTime;
use RdKafka\Message as KafkaMessage;

class Message implements MessageInterface
{
    private string       $body;
    private array        $headers   = [];
    private KafkaMessage $kafkaMessage;
    private ?int         $partition = null;
    private ?string      $key       = null;
    private DateTime     $createdAt;

    public function getBody(): string
    {
        return $this->body;
    }

    public function setBody(string $body): void
    {
        $this->body = $body;
    }

    public function getHeaders(): array
    {
        return $this->headers;
    }

    public function addHeader(string $key, string $value): void
    {
        $this->headers[$key] = $value;
    }

    public function getKafkaMessage(): KafkaMessage
    {
        return $this->kafkaMessage;
    }

    public function setKafkaMessage(KafkaMessage $kafkaMessage): void
    {
        $this->kafkaMessage = $kafkaMessage;
    }

    public function getPartition(): ?int
    {
        return $this->partition;
    }

    public function setPartition(?int $partition): void
    {
        $this->partition = $partition;
    }

    public function getKey(): ?string
    {
        return $this->key;
    }

    public function setKey(?string $key): void
    {
        $this->key = $key;
    }

    public function getCreatedAt(): DateTime
    {
        return $this->createdAt;
    }

    public function setCreatedAt(DateTime $createdAt): void
    {
        $this->createdAt = $createdAt;
    }
}