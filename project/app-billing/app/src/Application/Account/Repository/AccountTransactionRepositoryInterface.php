<?php

namespace App\Application\Account\Repository;

use App\Application\Account\Model\AccountModelInterface;

interface AccountTransactionRepositoryInterface
{
    public function sumByIdempotencyKey(AccountModelInterface $accountModel, string $idempotencyKey): int;
}