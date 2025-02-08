<?php

namespace App\Application\Advertisement\UseCase;

use App\Application\Advertisement\Factory\AdvertisementFactoryInterface;
use App\Application\Advertisement\Model\AdvertisementModelInterface;
use App\Application\Advertisement\Model\AdvertisementStatusEnum;
use App\Application\Advertisement\Repository\AdvertisementRepositoryInterface;
use App\Application\Advertisement\Saga\Saga;
use App\Application\Advertisement\Saga\AdvCreateSagaDto;
use App\Application\Advertisement\Saga\SagaException;
use App\Application\EntityStorage\EntityStorageServiceInterface;
use App\Application\EntityStorage\Exception\EntityStorageException;
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
        private Saga                             $advCreateSaga,
        private AdvertisementFactoryInterface    $advertisementFactory,
        private ProducerInterface                $producer,
        private MessageFabricInterface           $messageFabric,
        private LockFactoryInterface             $lockFactory,
        private EntityStorageServiceInterface    $entityStorageService,
        private AdvertisementRepositoryInterface $advertisementRepository,
    ) {
    }

    /**
     * @param AdvertisementCreateDataInterface $data
     * @return AdvertisementModelInterface
     * @throws JsonException
     * @throws LockException
     * @throws ProducerException
     * @throws SagaException
     * @throws EntityStorageException
     */
    public function do(AdvertisementCreateDataInterface $data): AdvertisementModelInterface
    {
        $lock = $this->lockFactory->create('pay:' . $data->getUserId());
        $lock->acquire();

        $advertisement = $this->advertisementRepository->findByIdempotencyKey($data->getIdempotencyKey());
        if (null !== $advertisement && AdvertisementStatusEnum::ACTIVE === $advertisement->getStatus()) {
            return $advertisement;
        }

        if (null === $advertisement) {
            $advertisement = $this->advertisementFactory->create();
            $advertisement->setUserId($data->getUserId());
            $advertisement->setTitle($data->getTitle());
            $advertisement->setText($data->getText());
            $advertisement->setCost(mb_strlen($data->getText()));
            $advertisement->setIdempotencyKey($data->getIdempotencyKey());
            $advertisement->setStatus(AdvertisementStatusEnum::PENDING);

            $this->entityStorageService->persist($advertisement);
            $this->entityStorageService->flush();
        }

        $dto = new AdvCreateSagaDto($advertisement, $data->getToken(), $data->getIdempotencyKey());

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