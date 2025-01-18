<?php

namespace App\Application\Advertisement\Saga;

interface SagaStepInterface
{
    /**
     * @throws SagaException
     */
    public function run(AdvCreateSagaDto $data): void;

    /**
     * @throws SagaException
     */
    public function compensation(AdvCreateSagaDto $data): void;
}