<?php

namespace App\Application\Kafka;

use DateTime;

interface MessageInterface
{
    public function getBody(): string;

    public function setBody(string $body): void;

    public function getHeaders(): array;

    public function addHeader(string $key, string $value): void;

    public function getPartition(): ?int;

    public function setPartition(?int $partition): void;

    public function getKey(): ?string;

    public function setKey(?string $key): void;

    public function getCreatedAt(): DateTime;

    public function setCreatedAt(DateTime $createdAt): void;
}