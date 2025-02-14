<?php

namespace App\Application\Advertisement\UseCase;

use App\Application\Advertisement\Factory\AdvertisementFactoryInterface;
use App\Application\Advertisement\Model\AdvertisementModelInterface;
use App\Application\Advertisement\Model\AdvertisementStatusEnum;
use App\Application\Advertisement\Repository\AdvertisementRepositoryInterface;
use App\Application\EntityStorage\EntityStorageServiceInterface;
use App\Application\EntityStorage\Exception\EntityStorageException;
use App\Application\Advertisement\UseCase\Contract\AdvertisementCreateDataInterface;

readonly class AdvertisementCreateAction
{
    public function __construct(
        private AdvertisementFactoryInterface    $advertisementFactory,
        private EntityStorageServiceInterface    $entityStorageService,
        private AdvertisementRepositoryInterface $advertisementRepository,
    ) {
    }

    /**
     * @param AdvertisementCreateDataInterface $data
     * @return AdvertisementModelInterface
     * @throws EntityStorageException
     */
    public function do(AdvertisementCreateDataInterface $data): AdvertisementModelInterface
    {
        $advertisement = $this->advertisementRepository->findByIdempotencyKey($data->getIdempotencyKey());
        if (null !== $advertisement) {
            return $advertisement;
        }

        $advertisement = $this->advertisementFactory->create();
        $advertisement->setUserId($data->getUserId());
        $advertisement->setTitle($data->getTitle());
        $advertisement->setText($data->getText());
        $advertisement->setIdempotencyKey($data->getIdempotencyKey());
        $advertisement->setStatus(AdvertisementStatusEnum::EDITED);

        $this->entityStorageService->persist($advertisement);
        $this->entityStorageService->flush();

        return $advertisement;
    }
}