<?php

namespace App\Application\Advertisement\Saga\Step;

use App\Application\Advertisement\Saga\AdvCreateSagaDto;
use App\Application\Advertisement\Saga\SagaException;
use App\Application\AppBonusClientInterface;

class BonusSagaStep extends AbstractSagaStep
{
    public function __construct(private readonly AppBonusClientInterface $client)
    {
    }

    /**
     * @inheritdoc
     */
    public function run(AdvCreateSagaDto $data): void
    {
        $advertisementModel = $data->getAdvertisementModel();

        $result = $this->client->debit(
            $advertisementModel->getCost(),
            $advertisementModel->getUserId(),
            $data->getToken()
        );

        if (false === $result) {
            throw new SagaException('Ошибка пополнения бонусного счёта');
        }
    }

    /**
     * @inheritdoc
     */
    public function compensation(AdvCreateSagaDto $data): void
    {
        $advertisementModel = $data->getAdvertisementModel();

        $this->client->credit(
            $advertisementModel->getCost(),
            $advertisementModel->getUserId(),
            $data->getToken()
        );
    }
}