<?php

namespace App\Infrastructure\Doctrine;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Exception\ORMException;
use App\Application\EntityStorage\EntityStorageServiceInterface;
use App\Application\EntityStorage\Exception\EntityStorageException;

readonly class EntityStorageService implements EntityStorageServiceInterface
{
    /**
     * @param EntityManager $entityManager
     */
    public function __construct(
        private EntityManagerInterface $entityManager,
    ) {}

    public function clear(): void
    {
        $this->entityManager->clear();
    }

    /**
     * @throws EntityStorageException
     */
    public function persist(object $object): void
    {
        try {
            $this->entityManager->persist($object);
        } catch (ORMException $exception) {
            throw new EntityStorageException($exception->getMessage(), $exception->getCode(), $exception);
        }
    }

    /**
     * @throws EntityStorageException
     */
    public function remove(object $object): void
    {
        try {
            $this->entityManager->remove($object);
        } catch (ORMException $exception) {
            throw new EntityStorageException($exception->getMessage(), $exception->getCode(), $exception);
        }
    }

    /**
     * @throws EntityStorageException
     */
    public function flush(): void
    {
        try {
            $this->entityManager->flush();
        } catch (ORMException $exception) {
            throw new EntityStorageException($exception->getMessage(), $exception->getCode(), $exception);
        }
    }
}