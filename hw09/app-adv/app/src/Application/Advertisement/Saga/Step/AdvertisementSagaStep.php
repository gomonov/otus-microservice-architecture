<?php

namespace App\Application\Advertisement\Saga\Step;

use App\Application\Advertisement\Model\AdvertisementStatusEnum;
use App\Application\Advertisement\Saga\AdvCreateSagaDto;
use App\Application\Advertisement\Saga\SagaException;
use App\Application\EntityStorage\EntityStorageServiceInterface;
use Throwable;

class AdvertisementSagaStep extends AbstractSagaStep
{
    public function __construct(private readonly EntityStorageServiceInterface $entityStorageService)
    {
    }

    /**
     * @inheritdoc
     */
    public function run(AdvCreateSagaDto $data): void
    {
        try {
            $data->getAdvertisementModel()->setStatus(AdvertisementStatusEnum::ACTIVE);
            $this->entityStorageService->flush();
        } catch (Throwable) {
            throw new SagaException('Ошибка сохранения заказа');
        }

    }

    /**
     * @inheritdoc
     */
    public function compensation(AdvCreateSagaDto $data): void
    {
    }
}