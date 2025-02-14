<?php

namespace App\Application\Bonus\Repository;

use App\Application\Bonus\Model\BonusModelInterface;
use DateTimeInterface;

interface BonusRepositoryInterface
{
    public function getByUserId(int $userId, bool $lock = false): ?BonusModelInterface;

    public function getSumByUser(DateTimeInterface $dateTime): array;
}