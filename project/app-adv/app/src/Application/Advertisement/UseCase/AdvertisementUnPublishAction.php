<?php

namespace App\Application\Advertisement\UseCase;

use App\Application\Advertisement\Exception\AdvertisementException;
use App\Application\Advertisement\Model\AdvertisementModelInterface;
use App\Application\Advertisement\Model\AdvertisementStatusEnum;
use App\Application\Advertisement\Repository\AdvertisementRepositoryInterface;
use App\Application\Advertisement\UseCase\Contract\AdvertisementBaseDataInterface;
use App\Application\EntityStorage\EntityStorageServiceInterface;
use App\Application\EntityStorage\Exception\EntityStorageException;
use App\Application\Kafka\Exception\ProducerException;
use App\Application\Kafka\MessageFabricInterface;
use App\Application\Kafka\ProducerInterface;

readonly class AdvertisementUnPublishAction
{
    public function __construct(
        private string                           $notifyTopic,
        private EntityStorageServiceInterface    $entityStorageService,
        private AdvertisementRepositoryInterface $advertisementRepository,
        private ProducerInterface                $producer,
        private MessageFabricInterface           $messageFabric,
    ) {
    }

    /**
     * @param AdvertisementBaseDataInterface $data
     * @return AdvertisementModelInterface
     * @throws AdvertisementException
     * @throws EntityStorageException
     * @throws ProducerException
     * @throws JsonException
     */
    public function do(AdvertisementBaseDataInterface $data): AdvertisementModelInterface
    {
        $advertisement = $this->advertisementRepository->findById($data->getId());
        if (null === $advertisement) {
            throw new AdvertisementException('Объявление не найдено');
        }

        if ($data->getUserId() !== $advertisement->getUserId()) {
            throw new AdvertisementException('Объявление не найдено');
        }

        if (false === AdvertisementStatusEnum::canChangeStatus(AdvertisementStatusEnum::VERIFIED, $advertisement)) {
            throw new AdvertisementException('Объявление не опубликовано');
        }

        $advertisement->setStatus(AdvertisementStatusEnum::VERIFIED);

        $this->entityStorageService->flush();

        $message = $this->messageFabric->create();
        $body    = [
            'userId' => $data->getUserId(),
            'text'   => sprintf('Объявление ID: %d снято с публикации', $advertisement->getId()),
        ];
        $message->setBody(json_encode($body, JSON_THROW_ON_ERROR));
        $this->producer->send($message, $this->notifyTopic);

        return $advertisement;
    }
}