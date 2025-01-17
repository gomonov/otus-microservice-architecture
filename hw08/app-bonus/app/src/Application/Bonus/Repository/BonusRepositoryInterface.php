<?php

namespace App\Application\Bonus\Repository;

use App\Application\Bonus\Model\BonusModelInterface;

interface BonusRepositoryInterface
{
    public function getByUserId(int $userId, bool $lock = false): ?BonusModelInterface;
}