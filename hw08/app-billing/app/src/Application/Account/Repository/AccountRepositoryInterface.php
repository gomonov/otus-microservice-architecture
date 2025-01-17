<?php

namespace App\Application\Account\Repository;

use App\Application\Account\Model\AccountModelInterface;

interface AccountRepositoryInterface
{
    public function getByUserId(int $userId, bool $lock = false): ?AccountModelInterface;
}