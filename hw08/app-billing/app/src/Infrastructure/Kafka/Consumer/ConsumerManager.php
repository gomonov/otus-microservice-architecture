<?php

namespace App\Infrastructure\Kafka\Consumer;

use App\Application\Kafka\Exception\ConsumerException;
use App\Infrastructure\Kafka\Configuration\ConfigurationManager;
use App\Infrastructure\Kafka\Message\MessageConverter;
use Psr\Log\LoggerInterface;
use RdKafka\Exception;
use RdKafka\KafkaConsumer;

readonly class ConsumerManager
{
    public function __construct(
        private ConsumerRepository   $consumerRepository,
        private ConfigurationManager $configurationManager,
        private MessageConverter     $messageConverter,
        private LoggerInterface      $logger,
    ) {
    }

    /**
     * @throws ConsumerException
     */
    public function resolveKafkaConsumer(ConsumerContext $context): void
    {
        $kafkaConsumer = $this->getConsumer($context->getGroup());
        try {
            $kafkaConsumer->subscribe([$context->getTopic()]);
        } catch (Exception $exception) {
            throw new ConsumerException($exception->getMessage());
        }
        $context->setKafkaConsumer($kafkaConsumer);
    }

    /**
     * @throws ConsumerException
     */
    public function consume(ConsumerContext $context): void
    {
        $consumer = $this->consumerRepository->getConsumer($context->getGroup());
        try {
            $kafkaMessage = $context->getKafkaConsumer()->consume($consumer->getTimeout());
            $this->logger->info('Receive message', ['body' => $kafkaMessage->payload]);
        } catch (Exception $exception) {
            throw new ConsumerException($exception->getMessage());
        }

        switch ($kafkaMessage->err) {
            case RD_KAFKA_RESP_ERR_NO_ERROR:
                $message = $this->messageConverter->toMessage($kafkaMessage);
                $consumer->execute($message);
                break;
            case RD_KAFKA_RESP_ERR__TIMED_OUT:
            case RD_KAFKA_RESP_ERR__PARTITION_EOF:
                break;
            case RD_KAFKA_RESP_ERR_UNKNOWN_TOPIC_OR_PART:
                $this->logger->warning($kafkaMessage->errstr(), ['code' => $kafkaMessage->err]);
                break;
            default:
                throw new ConsumerException($kafkaMessage->errstr(), $kafkaMessage->err);
        }
    }

    private function getConsumer(string $group): KafkaConsumer
    {
        $configuration = $this->configurationManager->getConsumerConfiguration();
        $configuration->set('group.id', $group);

        return new KafkaConsumer($configuration);
    }
}