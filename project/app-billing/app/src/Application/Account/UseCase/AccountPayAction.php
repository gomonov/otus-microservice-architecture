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
use App\Application\Kafka\Exception\ProducerException;
use App\Application\Kafka\MessageFabricInterface;
use App\Application\Kafka\ProducerInterface;
use JsonException;

readonly class AccountPayAction
{
    public function __construct(
        private string                                $notifyTopic,
        private AccountRepositoryInterface            $accountRepository,
        private EntityStorageServiceInterface         $entityStorageService,
        private AccountTransactionRepositoryInterface $accountTransactionRepository,
        private AccountTransactionFactoryInterface    $accountTransactionFactory,
        private MessageFabricInterface                $messageFabric,
        private ProducerInterface                     $producer,
    ) {
    }

    /**
     * @param AccountChangeDataInterface $data
     * @return AccountModelInterface
     * @throws AccountException
     * @throws EntityStorageException
     * @throws ProducerException
     * @throws JsonException
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
        if ($sum < 0) {
            return $account;
        }

        if ($data->getSum() > $account->getBalance()) {
            $this->entityStorageService->rollbackTransaction();
            throw new AccountException('Недостаточно средств на счёте');
        }

        $transaction = $this->accountTransactionFactory->create();
        $transaction->setAccount($account);
        $transaction->setValue(-1 * $data->getSum());
        $transaction->setIdempotencyKey($data->getIdempotencyKey());
        $this->entityStorageService->persist($transaction);

        $account->setBalance($account->getBalance() + $transaction->getValue());

        $this->entityStorageService->flush();
        $this->entityStorageService->commitTransaction();

        $message = $this->messageFabric->create();
        $message->setBody(
            json_encode(
                [
                    'userId' => $data->getUserId(),
                    'text'   => sprintf('Со счёта списана сумма %d (%d)', $data->getSum(), $account->getBalance()),
                ],
                JSON_THROW_ON_ERROR
            )
        );
        $this->producer->send($message, $this->notifyTopic);

        return $account;
    }
}