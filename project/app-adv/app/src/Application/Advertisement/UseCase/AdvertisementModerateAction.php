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
use App\Application\Uuid\UuidGeneratorInterface;
use JsonException;

readonly class AdvertisementModerateAction
{
    public function __construct(
        private string $moderateRequestTopic,
        private EntityStorageServiceInterface    $entityStorageService,
        private AdvertisementRepositoryInterface $advertisementRepository,
        private UuidGeneratorInterface           $uuidGenerator,
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

        if (false === AdvertisementStatusEnum::canChangeStatus(AdvertisementStatusEnum::MODERATED, $advertisement)) {
            throw new AdvertisementException('Объявление невозможно отправить на модерацию');
        }

        $advertisement->setStatus(AdvertisementStatusEnum::MODERATED);
        $advertisement->setModerKey($this->uuidGenerator->generate());

        $message = $this->messageFabric->create();
        $message->setBody(
            json_encode(
                ['uuid' => $advertisement->getModerKey(), 'text' => $advertisement->getText()],
                JSON_THROW_ON_ERROR
            )
        );
        $this->producer->send($message, $this->moderateRequestTopic);

        $this->entityStorageService->flush();

        return $advertisement;
    }
}