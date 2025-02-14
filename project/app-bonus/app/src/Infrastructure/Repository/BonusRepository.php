<?php

namespace App\Infrastructure\Repository;

use App\Application\Bonus\Model\BonusModelInterface;
use App\Application\Bonus\Repository\BonusRepositoryInterface;
use App\Infrastructure\Entity\Bonus;
use DateTimeInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\DBAL\LockMode;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Bonus>
 */
class BonusRepository extends ServiceEntityRepository implements BonusRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Bonus::class);
    }

    public function getByUserId(int $userId, bool $lock = false): ?BonusModelInterface
    {
        $bonus = $this->findOneBy(['userId' => $userId]);
        if (true === $lock && null !== $bonus) {
            $this->getEntityManager()->lock($bonus, LockMode::PESSIMISTIC_WRITE);
        }

        return $bonus;
    }

    public function getSumByUser(DateTimeInterface $dateTime): array
    {
        return $this->createQueryBuilder('b')
            ->select(['b.userId', 'b.bonus'])
            ->andWhere('b.updatedAt <= :dateTime')
            ->andWhere('b.bonus > 0')
            ->setParameter('dateTime', $dateTime)
            ->getQuery()
            ->getArrayResult();
    }
}
