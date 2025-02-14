<?php

namespace App\Application\Kafka;

interface ConsumerInterface
{
    public function execute(MessageInterface $message): bool;

    public function getTopic(): string;

    public function getGroup(): string;

    public function getTimeout(): int;

    public function getDescription(): string;

}