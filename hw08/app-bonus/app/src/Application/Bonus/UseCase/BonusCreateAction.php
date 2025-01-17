<?php

namespace App\Application\Bonus\UseCase;

use App\Application\Bonus\BonusException;
use App\Application\Bonus\Model\BonusModelInterface;
use App\Application\Bonus\Repository\BonusRepositoryInterface;
use App\Application\EntityStorage\EntityStorageServiceInterface;
use App\Application\EntityStorage\Exception\EntityStorageException;
use App\Infrastructure\Factory\BonusFactory;

readonly class BonusCreateAction
{
    public function __construct(
        private BonusFactory                  $bonusFactory,
        private BonusRepositoryInterface      $bonusRepository,
        private EntityStorageServiceInterface $entityStorageService,
    ) {
    }

    /**
     * @param int $userId
     * @return BonusModelInterface
     * @throws BonusException
     * @throws EntityStorageException
     */
    public function do(int $userId): BonusModelInterface
    {
        $bonus = $this->bonusRepository->getByUserId($userId);
        if (null !== $bonus) {
            throw new BonusException('Бонусный счёт для данного пользователя уже существует');
        }

        $bonus = $this->bonusFactory->create();
        $bonus->setUserId($userId);

        $this->entityStorageService->persist($bonus);
        $this->entityStorageService->flush();

        return $bonus;
    }
}