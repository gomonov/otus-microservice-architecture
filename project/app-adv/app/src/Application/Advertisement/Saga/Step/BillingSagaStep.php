<?php

namespace App\Application\Advertisement\Saga\Step;

use App\Application\Advertisement\Model\AdvertisementModelInterface;
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
    public function run(AdvertisementModelInterface $advertisement): void
    {
        $result = $this->client->pay(
            $advertisement->getCost(),
            $advertisement->getUserId(),
            $advertisement->getIdempotencyKey(),
        );

        if (false === $result) {
            throw new SagaException(
                'Ошибка выполнения платежа для объявления ID: ' . $advertisement->getId()
            );
        }
    }

    /**
     * @inheritdoc
     */
    public function compensation(AdvertisementModelInterface $advertisement): void
    {
        $this->client->rollback(
            $advertisement->getUserId(),
            $advertisement->getIdempotencyKey(),
        );
    }
}