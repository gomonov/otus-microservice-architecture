<?php

namespace App\UI\Consumer;

use App\Application\Kafka\Exception\ConsumerException;
use App\Infrastructure\Kafka\Consumer\ConsumerContext;
use App\Infrastructure\Kafka\Consumer\ConsumerManager;
use App\Infrastructure\Kafka\Consumer\ConsumerRepository;
use Psr\Log\LoggerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class KafkaConsumerCommand extends Command
{
    public function __construct(
        private readonly ConsumerManager      $consumerManager,
        private readonly ConsumerRepository   $consumerRepository,
        private readonly LoggerInterface      $logger,
    ) {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this->setName('kafka:consumer:start')
            ->setDescription('Executes a consumer')
            ->addArgument('group', InputArgument::REQUIRED, 'Consumer group');
        parent::configure();
    }

    /**
     * @param InputInterface  $input
     * @param OutputInterface $output
     * @return int
     * @throws ConsumerException
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $context = $this->buildContext($input);
        $this->registerHandlers($context);

        $this->consumerManager->resolveKafkaConsumer($context);

        $this->logger->info('Start consumer', [
            'topic' => $context->getTopic(),
            'group' => $context->getGroup(),
        ]);

        while (!$context->isStop()) {
            $this->consumerManager->consume($context);
        }

        $this->logger->info('Stop consumer', [
            'group'       => $context->getGroup(),
            'topic'       => $context->getTopic(),
            'stop reason' => $context->getStopReason(),
        ]);
        return Command::SUCCESS;
    }

    /**
     * @param InputInterface  $input
     * @return ConsumerContext
     */
    protected function buildContext(InputInterface $input): ConsumerContext
    {
        $context = new ConsumerContext();
        $context->setGroup($input->getArgument('group'));
        $consumer = $this->consumerRepository->getConsumer($context->getGroup());
        $context->setTopic($consumer->getTopic());

        return $context;
    }

    protected function registerHandlers(ConsumerContext $context): void
    {
        pcntl_async_signals(true);

        foreach ([SIGTERM, SIGINT] as $signal) {
            pcntl_signal($signal, [$context, 'sigStop']);
        }
    }
}