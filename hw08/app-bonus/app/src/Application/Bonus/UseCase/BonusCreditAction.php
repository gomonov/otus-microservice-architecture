<?php

namespace App\Application\Bonus\UseCase;

use App\Application\Bonus\BonusException;
use App\Application\Bonus\Model\BonusModelInterface;
use App\Application\Bonus\Repository\BonusRepositoryInterface;
use App\Application\Bonus\UseCase\Contract\BonusChangeDataInterface;
use App\Application\EntityStorage\EntityStorageServiceInterface;
use App\Application\EntityStorage\Exception\EntityStorageException;

readonly class BonusCreditAction
{
    public function __construct(
        private BonusRepositoryInterface      $bonusRepository,
        private EntityStorageServiceInterface $entityStorageService,
    ) {
    }

    /**
     * @throws BonusException
     * @throws EntityStorageException
     */
    public function do(BonusChangeDataInterface $data): BonusModelInterface
    {
        $this->entityStorageService->beginTransaction();

        $bonus = $this->bonusRepository->getByUserId($data->getUserId(), true);

        if (is_null($bonus)) {
            $this->entityStorageService->rollbackTransaction();
            throw new BonusException('Бонусный счёт для пользователя не найден');
        }

        if ($data->getSum() > $bonus->getBalance()) {
            $this->entityStorageService->rollbackTransaction();
            throw new BonusException('Недостаточно средств на бонусном счёте');
        }

        $bonus->setBalance($bonus->getBalance() - $data->getSum());

        $this->entityStorageService->flush();
        $this->entityStorageService->commitTransaction();

        return $bonus;
    }
}