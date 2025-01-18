<?php

namespace App\Application\Advertisement\UseCase;

use App\Application\Advertisement\Factory\AdvertisementFactoryInterface;
use App\Application\Advertisement\Model\AdvertisementModelInterface;
use App\Application\Advertisement\Saga\Saga;
use App\Application\Advertisement\Saga\AdvCreateSagaDto;
use App\Application\Advertisement\Saga\SagaException;
use App\Application\Kafka\Exception\ProducerException;
use App\Application\Kafka\MessageFabricInterface;
use App\Application\Kafka\ProducerInterface;
use App\Application\Advertisement\UseCase\Contract\AdvertisementCreateDataInterface;
use App\Application\Lock\LockException;
use App\Application\Lock\LockFactoryInterface;
use JsonException;

readonly class AdvertisementCreateAction
{
    public function __construct(
        private Saga                          $advCreateSaga,
        private AdvertisementFactoryInterface $advertisementFactory,
        private ProducerInterface             $producer,
        private MessageFabricInterface        $messageFabric,
        private LockFactoryInterface          $lockFactory,
    ) {
    }

    /**
     * @param AdvertisementCreateDataInterface $data
     * @return AdvertisementModelInterface
     * @throws JsonException
     * @throws LockException
     * @throws ProducerException
     * @throws SagaException
     */
    public function do(AdvertisementCreateDataInterface $data): AdvertisementModelInterface
    {
        $advertisement = $this->advertisementFactory->create();
        $advertisement->setUserId($data->getUserId());
        $advertisement->setTitle($data->getTitle());
        $advertisement->setText($data->getText());
        $advertisement->setCost(mb_strlen($data->getText()));

        $dto = new AdvCreateSagaDto($advertisement, $data->getToken(), $data->getEmail());

        $lock = $this->lockFactory->create('pay:' . $data->getUserId());
        $lock->acquire();
        try {
            try {
                $this->advCreateSaga->execute($dto);
                $messageBody = sprintf('Объявление на сумму %d успешно оплачено', $advertisement->getCost());
                return $advertisement;
            } catch (SagaException $exception) {
                $messageBody = $exception->getMessage();
                throw $exception;
            }
        } finally {
            $lock->release();
            if (false === empty($messageBody)) {
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
}