<?php

namespace App\Application\Advertisement\Saga\Step;

use App\Application\Advertisement\Saga\AdvCreateSagaDto;
use App\Application\Advertisement\Saga\SagaException;
use App\Application\AppBillingClientInterface;

class BillingSagaStep extends AbstractSagaStep
{
    public function __construct(private readonly AppBillingClientInterface $client)
    {
    }

    /**
     * @inheritdoc
     */
    public function run(AdvCreateSagaDto $data): void
    {
        $advertisementModel = $data->getAdvertisementModel();

        $result = $this->client->pay($advertisementModel->getCost(), $advertisementModel->getUserId(), $data->getToken());

        if (false === $result) {
            throw new SagaException('Ошибка выполнения платежа');
        }
    }

    /**
     * @inheritdoc
     */
    public function compensation(AdvCreateSagaDto $data): void
    {
        $advertisementModel = $data->getAdvertisementModel();

        $this->client->topUp(
            $advertisementModel->getCost(),
            $advertisementModel->getUserId(),
            $data->getToken()
        );


    }
}