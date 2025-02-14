<?php

namespace App\Application\Advertisement\UseCase;

use App\Application\Advertisement\Exception\AdvertisementException;
use App\Application\Advertisement\Model\AdvertisementModelInterface;
use App\Application\Advertisement\Model\AdvertisementStatusEnum;
use App\Application\Advertisement\Repository\AdvertisementRepositoryInterface;
use App\Application\Advertisement\UseCase\Contract\AdvertisementBaseDataInterface;
use App\Application\EntityStorage\EntityStorageServiceInterface;
use App\Application\EntityStorage\Exception\EntityStorageException;

readonly class AdvertisementUnArchiveAction
{
    public function __construct(
        private EntityStorageServiceInterface    $entityStorageService,
        private AdvertisementRepositoryInterface $advertisementRepository,
    ) {
    }

    /**
     * @param AdvertisementBaseDataInterface $data
     * @return AdvertisementModelInterface
     * @throws AdvertisementException
     * @throws EntityStorageException
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

        if (false === AdvertisementStatusEnum::canChangeStatus(AdvertisementStatusEnum::EDITED, $advertisement)) {
            throw new AdvertisementException('Объявление не находится в архиве');
        }

        $advertisement->setStatus(AdvertisementStatusEnum::EDITED);

        $this->entityStorageService->flush();

        return $advertisement;
    }
}