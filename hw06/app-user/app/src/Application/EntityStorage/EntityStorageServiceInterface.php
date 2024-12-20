<?php

namespace App\Application\EntityStorage;

use App\Application\EntityStorage\Exception\EntityStorageException;

interface EntityStorageServiceInterface
{
    public function clear(): void;

    /**
     * @throws EntityStorageException
     */
    public function persist(object $object): void;

    /**
     * @throws EntityStorageException
     */
    public function remove(object $object): void;

    /**
     * @throws EntityStorageException
     */
    public function flush(): void;
}