<?php

namespace App\Application\Advertisement\UseCase;

use App\Application\Advertisement\Model\AdvertisementStatusEnum;
use App\Application\Advertisement\Repository\AdvertisementRepositoryInterface;
use App\Application\Advertisement\UseCase\Contract\ModerateResultDataInterface;
use App\Application\EntityStorage\EntityStorageServiceInterface;
use App\Application\EntityStorage\Exception\EntityStorageException;
use App\Application\Kafka\Exception\ProducerException;
use App\Application\Kafka\MessageFabricInterface;
use App\Application\Kafka\ProducerInterface;
use JsonException;

readonly class ModerateResultAction
{
    public function __construct(
        private string                           $notifyTopic,
        private AdvertisementRepositoryInterface $repository,
        private EntityStorageServiceInterface    $entityStorageService,
        private MessageFabricInterface           $messageFabric,
        private ProducerInterface                $producer,
    ) {
    }

    /**
     * @param ModerateResultDataInterface $data
     * @return void
     * @throws EntityStorageException
     * @throws ProducerException
     * @throws JsonException
     */
    public function do(ModerateResultDataInterface $data): void
    {
        $advertisement = $this->repository->findByModerKey($data->getUuid());
        if (null === $advertisement) {
            return;
        }

        $messageBody = null;

        if (true === $data->getVerified()
            && true === AdvertisementStatusEnum::canChangeStatus(AdvertisementStatusEnum::VERIFIED, $advertisement)
        ) {
            $messageBody = sprintf('Объявление ID: %d успешно прошло модерацию', $advertisement->getId());
            $advertisement->setStatus(AdvertisementStatusEnum::VERIFIED);
        }

        if (false === $data->getVerified()
            && true === AdvertisementStatusEnum::canChangeStatus(AdvertisementStatusEnum::EDITED, $advertisement)
        ) {
            $messageBody = sprintf(
                'Объявление ID: %d не прошло модерацию. Причина: %s',
                $advertisement->getId(),
                $data->getReason()
            );
            $advertisement->setStatus(AdvertisementStatusEnum::EDITED);
            $advertisement->setModerFailReason($data->getReason());
        }

        $this->entityStorageService->flush();

        if (null !== $messageBody) {
            $message = $this->messageFabric->create();
            $message->setBody(
                json_encode(['userId' => $advertisement->getUserId(), 'text' => $messageBody], JSON_THROW_ON_ERROR)
            );
            $this->producer->send($message, $this->notifyTopic);
        }
    }
}