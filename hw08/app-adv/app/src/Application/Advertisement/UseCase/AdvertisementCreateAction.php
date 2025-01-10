<?php

namespace App\Application\Advertisement\UseCase;

use App\Application\Advertisement\Exception\AdvertisementException;
use App\Application\Advertisement\Factory\AdvertisementFactoryInterface;
use App\Application\Advertisement\Model\AdvertisementModelInterface;
use App\Application\AppBillingClientInterface;
use App\Application\EntityStorage\Exception\EntityStorageException;
use App\Application\Kafka\Exception\ProducerException;
use App\Application\Kafka\MessageFabricInterface;
use App\Application\Kafka\ProducerInterface;
use App\Application\Advertisement\UseCase\Contract\AdvertisementCreateDataInterface;
use App\Application\EntityStorage\EntityStorageServiceInterface;
use App\Application\Lock\LockException;
use App\Application\Lock\LockFactoryInterface;
use JsonException;

readonly class AdvertisementCreateAction
{
    public function __construct(
        private AdvertisementFactoryInterface $advertisementFactory,
        private EntityStorageServiceInterface $entityStorageService,
        private ProducerInterface             $producer,
        private MessageFabricInterface        $messageFabric,
        private AppBillingClientInterface     $appBillingClient,
        private LockFactoryInterface          $lockFactory,
    ) {
    }

    /**
     * @param AdvertisementCreateDataInterface $data
     * @return void
     * @throws AdvertisementException
     * @throws EntityStorageException
     * @throws ProducerException
     * @throws LockException
     * @throws JsonException
     */
    public function do(AdvertisementCreateDataInterface $data): AdvertisementModelInterface
    {
        $advertisement = $this->advertisementFactory->create();
        $advertisement->setUserId($data->getUserId());
        $advertisement->setTitle($data->getTitle());
        $advertisement->setText($data->getText());
        $advertisement->setCost(mb_strlen($data->getText()));

        $lock = $this->lockFactory->create('pay:' . $data->getUserId());
        $lock->acquire();
        try {
            $isPay = $this->appBillingClient->pay($advertisement->getCost(), $advertisement->getUserId(), $data->getToken());

            if (true === $isPay) {
                $this->entityStorageService->persist($advertisement);
                $this->entityStorageService->flush();

                $messageBody = sprintf('Объявление на сумму %d успешно оплачено', $advertisement->getCost());

                return $advertisement;
            }

            $messageBody = sprintf('На счёте недостаточно средств для оплаты объявления на сумму %d', $advertisement->getCost());
            throw new AdvertisementException($messageBody);
        } finally {
            $lock->release();
            $message = $this->messageFabric->create();
            $body = [
                'userId' => $data->getUserId(),
                'email' => $data->getEmail(),
                'text' => $messageBody,
            ];
            $message->setBody(json_encode($body, JSON_THROW_ON_ERROR));
            $this->producer->send($message, 'order.event');
        }
    }
}