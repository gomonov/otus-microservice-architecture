<?php

namespace App\Infrastructure\Repository;

use App\Application\Account\Model\AccountModelInterface;
use App\Application\Account\Repository\AccountTransactionRepositoryInterface;
use App\Infrastructure\Entity\AccountTransaction;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<AccountTransaction>
 */
class AccountTransactionRepository extends ServiceEntityRepository implements AccountTransactionRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, AccountTransaction::class);
    }

    public function sumByIdempotencyKey(AccountModelInterface $accountModel, string $idempotencyKey): int
    {
        return (int)$this->createQueryBuilder('at')
            ->select('SUM(at.value)')
            ->where(['at.idempotencyKey' => $idempotencyKey])
            ->andWhere(['at.account' => $accountModel])
            ->getQuery()
            ->getSingleScalarResult();
    }
}
