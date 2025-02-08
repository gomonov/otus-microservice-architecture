<?php

namespace App\Application\Bonus\UseCase;

use App\Application\Bonus\BonusException;
use App\Application\Bonus\Factory\BonusTransactionFactoryInterface;
use App\Application\Bonus\Model\BonusModelInterface;
use App\Application\Bonus\Repository\BonusRepositoryInterface;
use App\Application\Bonus\Repository\BonusTransactionRepositoryInterface;
use App\Application\Bonus\UseCase\Contract\BonusRollbackDataInterface;
use App\Application\EntityStorage\EntityStorageServiceInterface;
use App\Application\EntityStorage\Exception\EntityStorageException;

readonly class BonusRollbackAction
{
    public function __construct(
        private BonusRepositoryInterface            $bonusRepository,
        private EntityStorageServiceInterface       $entityStorageService,
        private BonusTransactionRepositoryInterface $bonusTransactionRepository,
        private BonusTransactionFactoryInterface    $bonusTransactionFactory,
    ) {
    }

    /**
     * @throws BonusException
     * @throws EntityStorageException
     */
    public function do(BonusRollbackDataInterface $data): BonusModelInterface
    {
        $this->entityStorageService->beginTransaction();

        $bonus = $this->bonusRepository->getByUserId($data->getUserId(), true);

        if (true === is_null($bonus)) {
            $this->entityStorageService->rollbackTransaction();
            throw new BonusException('Бонусный счёт для пользователя не найден');
        }

        $sum = $this->bonusTransactionRepository->sumByIdempotencyKey($bonus, $data->getIdempotencyKey());

        if (0 === $sum) {
            return $bonus;
        }

        $transaction = $this->bonusTransactionFactory->create();
        $transaction->setBonus($bonus);
        $transaction->setValue(-1 * $sum);
        $transaction->setIdempotencyKey($data->getIdempotencyKey());
        $this->entityStorageService->persist($transaction);

        $bonus->setBonus($bonus->getBonus() + $transaction->getValue());

        $this->entityStorageService->flush();
        $this->entityStorageService->commitTransaction();

        return $bonus;
    }
}