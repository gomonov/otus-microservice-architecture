<?php

namespace App\Infrastructure\Kafka\Message;

use DateTime;
use RdKafka\Message as KafkaMessage;

class MessageConverter
{
    public function toMessage(KafkaMessage $kafkaMessage): Message
    {
        $message = new Message();
        $message->setBody($kafkaMessage->payload);
        if ($kafkaMessage->headers) {
            foreach ($kafkaMessage->headers as $name => $value) {
                $message->addHeader($name, $value);
            }
        }
        $message->setKey($kafkaMessage->key);
        $message->setPartition($kafkaMessage->partition);
        $message->setKafkaMessage($kafkaMessage);
        $message->setCreatedAt(DateTime::createFromFormat('U', (int)($kafkaMessage->timestamp / 1000)));
        return $message;
    }
}