<?php

namespace App\Infrastructure\Repository;

use App\Application\User\Model\UserModelInterface;
use App\Application\User\Repository\UserRepositoryInterface;
use App\Infrastructure\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<User>
 */
class UserRepository extends ServiceEntityRepository implements UserRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);
    }

    public function getById(int $userId): ?UserModelInterface
    {
        return $this->find($userId);
    }
}
