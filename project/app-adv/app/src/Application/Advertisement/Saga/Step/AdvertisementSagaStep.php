<?php

namespace App\Application\Advertisement\Saga\Step;

use App\Application\Advertisement\Model\AdvertisementModelInterface;
use App\Application\Advertisement\Model\AdvertisementStatusEnum;
use App\Application\Advertisement\Saga\SagaException;
use App\Application\EntityStorage\EntityStorageServiceInterface;
use Throwable;

class AdvertisementSagaStep extends AbstractSagaStep
{
    public function __construct(
        private readonly EntityStorageServiceInterface $entityStorageService
    ) {
    }

    /**
     * @inheritdoc
     */
    public function run(AdvertisementModelInterface $advertisement): void
    {
        try {
            $advertisement->setStatus(AdvertisementStatusEnum::PUBLISHED);

            $this->entityStorageService->flush();
        } catch (Throwable) {
            throw new SagaException('Ошибка сохранения объявления ID: ' . $advertisement->getId());
        }
    }

    /**
     * @inheritdoc
     */
    public function compensation(AdvertisementModelInterface $advertisement): void
    {
    }
}