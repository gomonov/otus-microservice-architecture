<?php

namespace App\Infrastructure\Kafka\Configuration;

use InvalidArgumentException;
use Psr\Log\LoggerInterface;
use RdKafka\Conf;
use RdKafka\TopicConf;

class ConfigurationManager
{
    private const int FLUSH_TIMEOUT_SEC = 3;
    private const int FLUSH_RETRIES     = 3;

    private const int MAX_FLUSH_TIME = 10;

    private int $flushTimeoutSec;
    private int $flushReties;

    public function __construct(private readonly LoggerInterface $logger, private readonly array $config)
    {
        $this->flushTimeoutSec = (int)($this->config['flush.timeout_sec'] ?? self::FLUSH_TIMEOUT_SEC);
        $this->flushReties     = (int)($this->config['flush.retries'] ?? self::FLUSH_RETRIES);

        if ($this->flushReties * $this->flushTimeoutSec > self::MAX_FLUSH_TIME) {
            throw new InvalidArgumentException('Multiplication of parameters flush.timeout_sec and flush.retries should be less than ' . self::MAX_FLUSH_TIME);
        }
    }

    public function getProducerConfiguration(): Conf
    {
        $producerConfiguration = new Conf();
        foreach ($this->config['producer'] as $key => $value) {
            $producerConfiguration->set($key, $value);
        }
        $producerConfiguration->setDrMsgCb(
            function ($kafka, $message) {
                if ($message->err) {
                    $this->logger->error(
                        'Kafka: error send message',
                        [
                            'extra' => [
                                'message' => $message,
                                'kafka'   => $kafka,
                            ],
                        ]
                    );
                }
            }
        );
        $producerConfiguration->setErrorCb(
            function ($kafka, $err, $reason) {
                $this->logger->error(
                    sprintf("Kafka error: %s (reason: %s)\n", rd_kafka_err2str($err), $reason),
                    [
                        'extra' => [
                            'err'    => $err,
                            'kafka'  => $kafka,
                            'reason' => $reason,
                        ],
                    ]
                );
            }
        );
        return $producerConfiguration;
    }

    public function getConsumerConfiguration(): Conf
    {
        $consumerConfiguration = new Conf();
        foreach ($this->config['consumer'] as $key => $value) {
            $consumerConfiguration->set($key, $value);
        }
        $consumerConfiguration->setErrorCb(
            function ($kafka, $err, $reason) {
                $this->logger->error(
                    sprintf("Kafka error: %s (reason: %s)\n", rd_kafka_err2str($err), $reason),
                    [
                        'extra' => [
                            'err'    => $err,
                            'kafka'  => $kafka,
                            'reason' => $reason,
                        ],
                    ]
                );
            }
        );
        return $consumerConfiguration;
    }

    public function getTopicConfiguration(): TopicConf
    {
        $topicConfiguration = new TopicConf();
        foreach ($this->config['topic'] as $key => $value) {
            $topicConfiguration->set($key, $value);
        }
        return $topicConfiguration;
    }

    public function getFlushTimeout(): int
    {
        return $this->flushTimeoutSec * 1000;
    }

    public function getFlushRetries(): int
    {
        return $this->flushReties;
    }
}