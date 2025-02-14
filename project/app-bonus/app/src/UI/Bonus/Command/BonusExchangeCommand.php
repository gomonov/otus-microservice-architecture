<?php

namespace App\UI\Bonus\Command;

use App\Application\Bonus\UseCase\BonusExchangeAction;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class BonusExchangeCommand extends Command
{
    public function __construct(
        private readonly BonusExchangeAction $bonusExchangeAction,
    ) {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this->setName('bonus:exchange')->setDescription('Перевод денег с бонусного счёта');
        parent::configure();
    }

    /**
     * @param InputInterface  $input
     * @param OutputInterface $output
     * @return int
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->bonusExchangeAction->do();

        return Command::SUCCESS;
    }
}