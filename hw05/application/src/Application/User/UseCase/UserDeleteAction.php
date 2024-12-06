<?php

namespace App\Application\User\UseCase;

use App\Application\EntityStorage\EntityStorageServiceInterface;
use App\Application\EntityStorage\Exception\EntityStorageException;
use App\Application\User\Exception\UserNotFoundException;
use App\Application\User\Repository\UserRepositoryInterface;

readonly class UserDeleteAction
{
    public function __construct(
        private UserRepositoryInterface       $userRepository,
        private EntityStorageServiceInterface $entityStorageService,
    ) {
    }

    /**
     * @throws UserNotFoundException
     * @throws EntityStorageException
     */
    public function do(int $userId): void
    {
        $user = $this->userRepository->getById($userId);

        if (is_null($user)) {
            throw new UserNotFoundException('Пользователь не найден');
        }

        $this->entityStorageService->remove($user);

        $this->entityStorageService->flush();
    }
}