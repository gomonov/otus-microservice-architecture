<?php

namespace App\Application\Account\UseCase;

use App\Application\Account\Exception\AccountException;
use App\Application\Account\Model\AccountModelInterface;
use App\Application\Account\Repository\AccountRepositoryInterface;
use App\Application\Account\UseCase\Contract\AccountChangeDataInterface;
use App\Application\EntityStorage\EntityStorageServiceInterface;
use App\Application\EntityStorage\Exception\EntityStorageException;

readonly class AccountTopUpAction
{
    public function __construct(
        private AccountRepositoryInterface    $accountRepository,
        private EntityStorageServiceInterface $entityStorageService,
    ) {
    }

    /**
     * @throws AccountException
     * @throws EntityStorageException
     */
    public function do(AccountChangeDataInterface $data): AccountModelInterface
    {
        $this->entityStorageService->beginTransaction();

        $account = $this->accountRepository->getByUserId($data->getUserId(), true);

        if (is_null($account)) {
            $this->entityStorageService->rollbackTransaction();
            throw new AccountException('Счёт для пользователя не найден');
        }

        $account->setBalance($account->getBalance() + $data->getSum());

        $this->entityStorageService->flush();
        $this->entityStorageService->commitTransaction();

        return $account;
    }
}