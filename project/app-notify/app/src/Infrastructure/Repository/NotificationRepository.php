<?php

namespace App\Infrastructure\Repository;

use App\Application\Notification\Model\NotificationModelInterface;
use App\Application\Notification\Repository\NotificationRepositoryInterface;
use App\Infrastructure\Entity\Notification;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\DBAL\LockMode;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Notification>
 */
class NotificationRepository extends ServiceEntityRepository implements NotificationRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Notification::class);
    }


    public function getAllByUserId(int $userId, bool $lock = false): array
    {
        return $this->findBy(['userId' => $userId]);
    }
}
