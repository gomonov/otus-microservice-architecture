<?php

namespace App\Application\Advertisement\UseCase;

use App\Application\Advertisement\Exception\AdvertisementException;
use App\Application\Advertisement\Model\AdvertisementModelInterface;
use App\Application\Advertisement\Model\AdvertisementStatusEnum;
use App\Application\Advertisement\Repository\AdvertisementRepositoryInterface;
use App\Application\Advertisement\Saga\Saga;
use App\Application\Advertisement\Saga\SagaException;
use App\Application\Advertisement\UseCase\Contract\AdvertisementPublishDataInterface;
use App\Application\EntityStorage\EntityStorageServiceInterface;
use App\Application\EntityStorage\Exception\EntityStorageException;
use App\Application\Kafka\Exception\ProducerException;
use App\Application\Kafka\MessageFabricInterface;
use App\Application\Kafka\ProducerInterface;
use JsonException;

readonly class AdvertisementPublishAction
{
    public function __construct(
        private string                           $notifyTopic,
        private Saga                             $advCreateSaga,
        private ProducerInterface                $producer,
        private MessageFabricInterface           $messageFabric,
        private EntityStorageServiceInterface    $entityStorageService,
        private AdvertisementRepositoryInterface $advertisementRepository,
    ) {
    }

    /**
     * @param AdvertisementPublishDataInterface $data
     * @return AdvertisementModelInterface
     * @throws AdvertisementException
     * @throws EntityStorageException
     * @throws ProducerException
     * @throws SagaException
     * @throws JsonException
     */
    public function do(AdvertisementPublishDataInterface $data): AdvertisementModelInterface
    {
        $advertisement = $this->advertisementRepository->findById($data->getId());

        if (null === $advertisement) {
            throw new AdvertisementException('Объявление не найдено');
        }

        if ($data->getUserId() !== $advertisement->getUserId()) {
            throw new AdvertisementException('Объявление не найдено');
        }

        if (AdvertisementStatusEnum::PUBLISHED === $advertisement->getStatus()) {
            return $advertisement;
        }

        if (true === AdvertisementStatusEnum::canChangeStatus(AdvertisementStatusEnum::PENDING, $advertisement)) {
            $advertisement->setStatus(AdvertisementStatusEnum::PENDING);
        }

        # если выше статус pending не установили, значит объявление вообще не в подходящем статусе
        if (AdvertisementStatusEnum::PENDING !== $advertisement->getStatus()) {
            throw new AdvertisementException('Объявление нельзя опубликовать');
        }

        $this->entityStorageService->flush();

        try {
            try {
                $this->advCreateSaga->execute($advertisement);
                $messageBody = sprintf(
                    'Объявление ID: %d на сумму %d успешно оплачено',
                    $advertisement->getId(),
                    $advertisement->getCost()
                );
                return $advertisement;
            } catch (SagaException $exception) {
                $messageBody = $exception->getMessage();
                $advertisement->setStatus(AdvertisementStatusEnum::VERIFIED);

                $this->entityStorageService->flush();
                throw $exception;
            }
        } finally {
            if (false === empty($messageBody)) {
                $message = $this->messageFabric->create();
                $body    = [
                    'userId' => $data->getUserId(),
                    'text'   => $messageBody,
                ];
                $message->setBody(json_encode($body, JSON_THROW_ON_ERROR));
                $this->producer->send($message, $this->notifyTopic);
            }
        }
    }
}