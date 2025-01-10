<?php

namespace App\Application\Account\UseCase;

use App\Application\Account\Exception\AccountException;
use App\Application\Account\Model\AccountModelInterface;
use App\Application\Account\Repository\AccountRepositoryInterface;
use App\Application\EntityStorage\EntityStorageServiceInterface;
use App\Application\EntityStorage\Exception\EntityStorageException;
use App\Infrastructure\Factory\AccountFactory;

readonly class AccountCreateAction
{
    public function __construct(
        private AccountFactory                $accountFactory,
        private AccountRepositoryInterface    $accountRepository,
        private EntityStorageServiceInterface $entityStorageService,
    ) {
    }

    /**
     * @param int $userId
     * @return AccountModelInterface
     * @throws AccountException
     * @throws EntityStorageException
     */
    public function do(int $userId): AccountModelInterface
    {
        $account = $this->accountRepository->getByUserId($userId);
        if (null !== $account) {
            throw new AccountException('Счёт для данного пользователя уже существует');
        }

        $account = $this->accountFactory->create();
        $account->setUserId($userId);

        $this->entityStorageService->persist($account);
        $this->entityStorageService->flush();

        return $account;
    }
}