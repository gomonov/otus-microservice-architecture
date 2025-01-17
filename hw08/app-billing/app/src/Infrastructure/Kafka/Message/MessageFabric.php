<?php

namespace App\Infrastructure\Kafka\Message;

use App\Application\Kafka\MessageFabricInterface;
use App\Application\Kafka\MessageInterface;

class MessageFabric implements MessageFabricInterface
{
    public function create(): MessageInterface
    {
        return new Message();
    }
}