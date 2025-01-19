<?php

namespace App\Application\Bonus\UseCase;

use App\Application\Bonus\BonusException;
use App\Application\Bonus\Factory\BonusTransactionFactoryInterface;
use App\Application\Bonus\Model\BonusModelInterface;
use App\Application\Bonus\Repository\BonusRepositoryInterface;
use App\Application\Bonus\Repository\BonusTransactionRepositoryInterface;
use App\Application\Bonus\UseCase\Contract\BonusAddDataInterface;
use App\Application\EntityStorage\EntityStorageServiceInterface;
use App\Application\EntityStorage\Exception\EntityStorageException;

readonly class BonusAddAction
{
    public function __construct(
        private BonusRepositoryInterface            $bonusRepository,
        private BonusTransactionRepositoryInterface $bonusTransactionRepository,
        private EntityStorageServiceInterface       $entityStorageService,
        private BonusTransactionFactoryInterface    $bonusTransactionFactory,
        private int                                 $bonusPercent,
    ) {
    }

    /**
     * @throws BonusException
     * @throws EntityStorageException
     */
    public function do(BonusAddDataInterface $data): BonusModelInterface
    {
        $this->entityStorageService->beginTransaction();

        $bonus = $this->bonusRepository->getByUserId($data->getUserId(), true);

        if (true === is_null($bonus)) {
            $this->entityStorageService->rollbackTransaction();
            throw new BonusException('Бонусный счёт для пользователя не найден');
        }

        $sum = $this->bonusTransactionRepository->sumByIdempotencyKey($bonus, $data->getIdempotencyKey());

        # уже провели пополнение бонусного счёта
        if ($sum > 0) {
            return $bonus;
        }

        $add = (int)round($data->getSum() * $this->bonusPercent / 100);

        $transaction = $this->bonusTransactionFactory->create();
        $transaction->setBonus($bonus);
        $transaction->setValue($add);
        $transaction->setIdempotencyKey($data->getIdempotencyKey());
        $this->entityStorageService->persist($transaction);

        $bonus->setBonus($bonus->getBonus() + $transaction->getValue());

        $this->entityStorageService->flush();
        $this->entityStorageService->commitTransaction();

        return $bonus;
    }
}