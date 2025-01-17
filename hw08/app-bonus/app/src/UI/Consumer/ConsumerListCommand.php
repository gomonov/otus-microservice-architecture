<?php

namespace App\UI\Consumer;

use App\Infrastructure\Kafka\Consumer\ConsumerRepository;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class ConsumerListCommand extends Command
{
    public function __construct(
        private readonly ConsumerRepository $consumerRepository
    ) {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this->setName('kafka:consumer:list')->setDescription('Consumers list');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io        = new SymfonyStyle($input, $output);
        $consumers = $this->consumerRepository->getConsumers();
        $io->table(
            ['Group', 'Topic', 'Timeout', 'Description'],
            array_map(static function ($consumer) {
                return [
                    '<comment>' . $consumer->getGroup() . '</comment>',
                    $consumer->getTopic(),
                    $consumer->getTimeout(),
                    $consumer->getDescription(),
                ];
            }, $consumers)
        );
        return Command::SUCCESS;
    }
}