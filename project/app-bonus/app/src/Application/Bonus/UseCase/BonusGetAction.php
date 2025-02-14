<?php

namespace App\Application\Bonus\UseCase;

use App\Application\Bonus\BonusException;
use App\Application\Bonus\Model\BonusModelInterface;
use App\Application\Bonus\Repository\BonusRepositoryInterface;

readonly class BonusGetAction
{
    public function __construct(
        private BonusRepositoryInterface $bonusRepository,
    ) {
    }

    /**
     * @throws BonusException
     */
    public function do(int $userId): BonusModelInterface
    {
        $bonus = $this->bonusRepository->getByUserId($userId);

        if (is_null($bonus)) {
            throw new BonusException('Бонусный счёт не найден');
        }

        return $bonus;
    }
}