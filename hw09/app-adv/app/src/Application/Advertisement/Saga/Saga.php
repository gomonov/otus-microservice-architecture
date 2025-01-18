<?php

namespace App\Application\Advertisement\Saga;

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
        AdvertisementSagaStep $orderSagaStep
    ) {
        $bonusSagaStep->setNextStep($billingSagaStep);
        $billingSagaStep->setNextStep($orderSagaStep);
        $this->startStep = $bonusSagaStep;
    }

    /**
     * @throws SagaException
     */
    public function execute(AdvCreateSagaDto $advCreateSagaDto): void
    {
        $this->startStep->commit($advCreateSagaDto);
    }
}