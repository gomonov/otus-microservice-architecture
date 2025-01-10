<?php

namespace App\Infrastructure\Repository;

use App\Application\Account\Model\AccountModelInterface;
use App\Application\Account\Repository\AccountRepositoryInterface;
use App\Infrastructure\Entity\Account;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\DBAL\LockMode;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Account>
 */
class AccountRepository extends ServiceEntityRepository implements AccountRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Account::class);
    }

    public function getByUserId(int $userId, bool $lock = false): ?AccountModelInterface
    {
        $account = $this->findOneBy(['userId' => $userId]);
        if (true === $lock && null !== $account) {
            $this->getEntityManager()->lock($account, LockMode::PESSIMISTIC_WRITE);
        }

        return $account;
    }
}
