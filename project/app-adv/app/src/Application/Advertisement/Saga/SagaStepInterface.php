<?php

namespace App\Application\Advertisement\Saga;

use App\Application\Advertisement\Model\AdvertisementModelInterface;

interface SagaStepInterface
{
    /**
     * @throws SagaException
     */
    public function run(AdvertisementModelInterface $advertisement): void;

    /**
     * @throws SagaException
     */
    public function compensation(AdvertisementModelInterface $advertisement): void;
}