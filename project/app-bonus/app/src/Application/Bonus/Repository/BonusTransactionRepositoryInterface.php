<?php

namespace App\Application\Bonus\Repository;

use App\Application\Bonus\Model\BonusModelInterface;

interface BonusTransactionRepositoryInterface
{
    public function sumByIdempotencyKey(BonusModelInterface $bonusModel, string $idempotencyKey): int;
}