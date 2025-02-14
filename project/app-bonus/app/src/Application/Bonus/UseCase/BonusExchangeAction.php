<?php

namespace App\Application\Bonus\UseCase;

use App\Application\AppBillingClientInterface;
use App\Application\Bonus\BonusException;
use App\Application\Bonus\Factory\BonusTransactionFactoryInterface;
use App\Application\Bonus\Repository\BonusRepositoryInterface;
use App\Application\EntityStorage\EntityStorageServiceInterface;
use App\Application\EntityStorage\Exception\EntityStorageException;
use App\Application\Uuid\UuidGeneratorInterface;
use DateTime;
use Exception;

readonly class BonusExchangeAction
{
    public function __construct(
        private int                                 $minutesDiff,
        private BonusRepositoryInterface            $repository,
        private EntityStorageServiceInterface       $entityStorageService,
        private AppBillingClientInterface           $appBillingClient,
        private UuidGeneratorInterface              $uuidGenerator,
        private BonusRepositoryInterface            $bonusRepository,
        private BonusTransactionFactoryInterface    $bonusTransactionFactory,
    ) {
    }

    public function do(): void
    {
        $data = $this->repository->getSumByUser((new DateTime())->modify('-' . $this->minutesDiff . ' minutes'));
        foreach ($data as $item) {
            $uuid = $this->uuidGenerator->generate();
            if (true === $this->appBillingClient->exchange($item['bonus'], $item['userId'], $uuid)) {
                try {
                    $this->entityStorageService->beginTransaction();

                    $bonus = $this->bonusRepository->getByUserId($item['userId'], true);

                    if (true === is_null($bonus)) {
                        $this->entityStorageService->rollbackTransaction();
                        throw new BonusException();
                    }

                    $transaction = $this->bonusTransactionFactory->create();
                    $transaction->setBonus($bonus);
                    $transaction->setValue(-1 * $item['bonus']);
                    $transaction->setIdempotencyKey($uuid);

                    $this->entityStorageService->persist($transaction);

                    $bonus->setBonus($bonus->getBonus() + $transaction->getValue());

                    $this->entityStorageService->flush();
                    $this->entityStorageService->commitTransaction();
                } catch (Exception) {
                    try {
                        $this->entityStorageService->rollbackTransaction();
                    } catch (EntityStorageException) {}
                    $this->appBillingClient->rollback($item['userId'], $uuid);
                }
            }
        }
    }
}