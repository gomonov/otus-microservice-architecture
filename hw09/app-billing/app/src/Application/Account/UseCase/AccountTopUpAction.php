<?php

namespace App\Application\Account\UseCase;

use App\Application\Account\Exception\AccountException;
use App\Application\Account\Factory\AccountTransactionFactoryInterface;
use App\Application\Account\Model\AccountModelInterface;
use App\Application\Account\Repository\AccountRepositoryInterface;
use App\Application\Account\Repository\AccountTransactionRepositoryInterface;
use App\Application\Account\UseCase\Contract\AccountChangeDataInterface;
use App\Application\EntityStorage\EntityStorageServiceInterface;
use App\Application\EntityStorage\Exception\EntityStorageException;

readonly class AccountTopUpAction
{
    public function __construct(
        private AccountRepositoryInterface            $accountRepository,
        private EntityStorageServiceInterface         $entityStorageService,
        private AccountTransactionRepositoryInterface $accountTransactionRepository,
        private AccountTransactionFactoryInterface    $accountTransactionFactory,
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

        $sum = $this->accountTransactionRepository->sumByIdempotencyKey($account, $data->getIdempotencyKey());

        if ($sum > 0) {
            return $account;
        }

        $transaction = $this->accountTransactionFactory->create();
        $transaction->setAccount($account);
        $transaction->setValue($data->getSum());
        $transaction->setIdempotencyKey($data->getIdempotencyKey());
        $this->entityStorageService->persist($transaction);

        $account->setBalance($account->getBalance() + $data->getSum());

        $this->entityStorageService->flush();
        $this->entityStorageService->commitTransaction();

        return $account;
    }
}