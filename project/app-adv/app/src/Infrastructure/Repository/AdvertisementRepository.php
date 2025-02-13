<?php

namespace App\Infrastructure\Repository;

use App\Application\Advertisement\Model\AdvertisementModelInterface;
use App\Application\Advertisement\Model\AdvertisementStatusEnum;
use App\Application\Advertisement\Repository\AdvertisementRepositoryInterface;
use App\Infrastructure\Entity\Advertisement;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Advertisement>
 */
class AdvertisementRepository extends ServiceEntityRepository implements AdvertisementRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Advertisement::class);
    }

    public function findByIdempotencyKey(string $idempotencyKey): ?AdvertisementModelInterface
    {
        return $this->findOneBy(['idempotencyKey' => $idempotencyKey]);
    }

    public function findById(int $advId): ?AdvertisementModelInterface
    {
        return $this->find($advId);
    }

    public function findByModerKey(string $moderKey): ?AdvertisementModelInterface
    {
        return $this->findOneBy(['moderKey' => $moderKey]);
    }

    public function findAllPublish(): array
    {
        return $this->findBy(['status' => AdvertisementStatusEnum::PUBLISHED], ['createdAt' => 'DESC']);
    }

    public function findAllByUser(int $userId): array
    {
        return $this->findBy(['userId' => $userId], ['id' => 'ASC']);
    }
}
