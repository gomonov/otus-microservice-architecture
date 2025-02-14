<?php

namespace App\Application\Advertisement\Saga\Step;

use App\Application\Advertisement\Model\AdvertisementModelInterface;
use App\Application\Advertisement\Saga\SagaException;
use App\Application\Advertisement\Saga\SagaStepInterface;

abstract class AbstractSagaStep implements SagaStepInterface
{
    private ?AbstractSagaStep $previousStep = null;

    private ?AbstractSagaStep $nextStep = null;

    public function setNextStep(AbstractSagaStep $nextStep): void
    {
        $this->nextStep = $nextStep;
    }

    abstract public function run(AdvertisementModelInterface $advertisement): void;

    abstract public function compensation(AdvertisementModelInterface $advertisement): void;

    /**
     * @throws SagaException
     */
    public function commit(AdvertisementModelInterface $advertisement): void
    {
        $this->run($advertisement);
        try {
            $this->nextStep?->commit($advertisement);
        } catch (SagaException $exception) {
            $this->rollback($advertisement);
            throw $exception;
        }
    }

    public function rollback(AdvertisementModelInterface $advertisement): void
    {
        try {
            $this->compensation($advertisement);
        } catch (SagaException) {
        }
        $this->previousStep?->rollback($advertisement);
    }
}