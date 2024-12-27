<?php

namespace App\Infrastructure\Kafka\Producer;

use App\Application\Kafka\Exception\ProducerException;
use App\Application\Kafka\MessageInterface;
use App\Application\Kafka\ProducerInterface;
use App\Infrastructure\Kafka\Configuration\ConfigurationManager;
use Exception;
use Psr\Log\LoggerInterface;
use RdKafka\Producer as KafkaProducer;
use RdKafka\ProducerTopic;
use RuntimeException;

class Producer implements ProducerInterface
{
    private KafkaProducer $kafkaProducer;

    /**
     * @var ProducerTopic[]
     */
    private array $producerTopics;

    public function __construct(
        private readonly ConfigurationManager $configurationManager,
        private readonly LoggerInterface      $logger,
    ) {
    }

    /**
     * @throws ProducerException
     */
    public function send(MessageInterface $message, string $topicName): void
    {
        try {
            $this->getTopic($topicName)->producev(
                $message->getPartition() ?? RD_KAFKA_PARTITION_UA,
                0,
                $message->getBody(),
                $message->getKey(),
                $message->getHeaders()
            );

            $this->getProducer()->poll(0);

            $flushRetries = 1;
            while (true) {
                $result = $this->getProducer()->flush($this->configurationManager->getFlushTimeout());
                if (RD_KAFKA_RESP_ERR_NO_ERROR === $result || $flushRetries === $this->configurationManager->getFlushRetries(
                    )) {
                    break;
                }
                $flushRetries++;
            }
            if (RD_KAFKA_RESP_ERR_NO_ERROR !== $result) {
                throw new RuntimeException('Was unable to flush, messages might be lost!');
            }
            $this->logger->info('Send message', [
                'topic' => $topicName,
                'body'  => $message->getBody(),
            ]);
        } catch (Exception $exception) {
            throw new ProducerException($exception->getMessage(), $exception->getCode(), $exception);
        }
    }

    private function getProducer(): KafkaProducer
    {
        if (empty($this->kafkaProducer)) {
            $this->kafkaProducer = new KafkaProducer($this->configurationManager->getProducerConfiguration());
        }
        return $this->kafkaProducer;
    }

    private function getTopic(string $topicName): ProducerTopic
    {
        if (empty($this->producerTopics[$topicName])) {
            $this->producerTopics[$topicName] = $this->getProducer()->newTopic(
                $topicName,
                $this->configurationManager->getTopicConfiguration()
            );
        }
        return $this->producerTopics[$topicName];
    }
}