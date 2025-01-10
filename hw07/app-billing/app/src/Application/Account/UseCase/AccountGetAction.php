<?php

namespace App\Application\Account\UseCase;

use App\Application\Account\Exception\AccountException;
use App\Application\Account\Model\AccountModelInterface;
use App\Application\Account\Repository\AccountRepositoryInterface;
use App\Application\User\Repository\UserRepositoryInterface;

readonly class AccountGetAction
{
    public function __construct(
        private AccountRepositoryInterface $accountRepository,
    ) {
    }

    /**
     * @throws AccountException
     */
    public function do(int $userId): AccountModelInterface
    {
        $account = $this->accountRepository->getByUserId($userId);

        if (is_null($account)) {
            throw new AccountException('Пользователь не найден');
        }

        return $account;
    }
}