<?php

namespace App\Application\Advertisement\Saga\Step;

use App\Application\Advertisement\Saga\AdvCreateSagaDto;
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

    abstract public function run(AdvCreateSagaDto $data): void;

    abstract public function compensation(AdvCreateSagaDto $data): void;

    /**
     * @throws SagaException
     */
    public function commit(AdvCreateSagaDto $data): void
    {
        $this->run($data);
        try {
            $this->nextStep?->commit($data);
        } catch (SagaException $exception) {
            $this->rollback($data);
            throw $exception;
        }
    }

    public function rollback(AdvCreateSagaDto $data): void
    {
        try {
            $this->compensation($data);
        } catch (SagaException) {
        }
        $this->previousStep?->rollback($data);
    }
}