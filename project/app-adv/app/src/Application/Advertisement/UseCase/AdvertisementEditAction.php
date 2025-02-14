<?php

namespace App\Application\Advertisement\UseCase;

use App\Application\Advertisement\Exception\AdvertisementException;
use App\Application\Advertisement\Model\AdvertisementModelInterface;
use App\Application\Advertisement\Model\AdvertisementStatusEnum;
use App\Application\Advertisement\Repository\AdvertisementRepositoryInterface;
use App\Application\Advertisement\UseCase\Contract\AdvertisementEditDataInterface;
use App\Application\EntityStorage\EntityStorageServiceInterface;
use App\Application\EntityStorage\Exception\EntityStorageException;

readonly class AdvertisementEditAction
{
    public function __construct(
        private EntityStorageServiceInterface    $entityStorageService,
        private AdvertisementRepositoryInterface $advertisementRepository,
    ) {
    }

    /**
     * @param AdvertisementEditDataInterface $data
     * @return AdvertisementModelInterface
     * @throws AdvertisementException
     * @throws EntityStorageException
     */
    public function do(AdvertisementEditDataInterface $data): AdvertisementModelInterface
    {
        $advertisement = $this->advertisementRepository->findById($data->getId());
        if (null === $advertisement) {
            throw new AdvertisementException('Объявление не найдено');
        }

        if ($data->getUserId() !== $advertisement->getUserId()) {
            throw new AdvertisementException('Объявление не найдено');
        }

        if (false === AdvertisementStatusEnum::canChangeStatus(AdvertisementStatusEnum::EDITED, $advertisement)) {
            throw new AdvertisementException('Нельзя редактировать объявление');
        }

        $advertisement->setStatus(AdvertisementStatusEnum::EDITED);
        $advertisement->setTitle($data->getTitle());
        $advertisement->setText($data->getText());
        $advertisement->setModerKey(null);
        $advertisement->setModerFailReason(null);

        $this->entityStorageService->flush();

        return $advertisement;
    }
}