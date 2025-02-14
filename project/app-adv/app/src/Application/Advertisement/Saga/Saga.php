<?php

namespace App\Application\Advertisement\Saga;

use App\Application\Advertisement\Model\AdvertisementModelInterface;
use App\Application\Advertisement\Saga\Step\AbstractSagaStep;
use App\Application\Advertisement\Saga\Step\BillingSagaStep;
use App\Application\Advertisement\Saga\Step\BonusSagaStep;
use App\Application\Advertisement\Saga\Step\AdvertisementSagaStep;

class Saga
{
    private AbstractSagaStep $startStep;

    public function __construct(
        BillingSagaStep       $billingSagaStep,
        BonusSagaStep         $bonusSagaStep,
        AdvertisementSagaStep $advertisementSagaStep
    ) {
        $bonusSagaStep->setNextStep($billingSagaStep);
        $billingSagaStep->setNextStep($advertisementSagaStep);
        $this->startStep = $bonusSagaStep;
    }

    /**
     * @throws SagaException
     */
    public function execute(AdvertisementModelInterface $advertisement): void
    {
        $this->startStep->commit($advertisement);
    }
}