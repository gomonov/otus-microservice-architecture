<?php

namespace App\Infrastructure\Repository;

use App\Application\Bonus\Model\BonusModelInterface;
use App\Application\Bonus\Repository\BonusTransactionRepositoryInterface;
use App\Infrastructure\Entity\BonusTransaction;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<BonusTransaction>
 */
class BonusTransactionRepository extends ServiceEntityRepository implements BonusTransactionRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, BonusTransaction::class);
    }

    public function sumByIdempotencyKey(BonusModelInterface $bonusModel, string $idempotencyKey): int
    {
        return (int)$this->createQueryBuilder('bt')
            ->select('SUM(bt.value)')
            ->where(['bt.idempotencyKey' => $idempotencyKey])
            ->andWhere(['bt.bonus' => $bonusModel])
            ->getQuery()
            ->getSingleScalarResult();
    }
}
