<?php

namespace App\Application\User\UseCase;

use App\Application\EntityStorage\EntityStorageServiceInterface;
use App\Application\EntityStorage\Exception\EntityStorageException;
use App\Application\User\Exception\UserException;
use App\Application\User\Model\UserModelInterface;
use App\Application\User\Repository\UserRepositoryInterface;
use App\Application\User\UseCase\Contract\UserUpdateDataInterface;

readonly class UserUpdateAction
{
    public function __construct(
        private UserRepositoryInterface       $userRepository,
        private EntityStorageServiceInterface $entityStorageService,
    ) {
    }

    /**
     * @throws UserException
     * @throws EntityStorageException
     */
    public function do(UserUpdateDataInterface $data): UserModelInterface
    {
        $user = $this->userRepository->getById($data->getId());

        if (is_null($user)) {
            throw new UserException('Пользователь не найден');
        }

        if (null !== $data->getFirstName()) {
            $user->setFirstName($data->getFirstName());
        }

        if (null !== $data->getLastName()) {
            $user->setLastName($data->getLastName());
        }

        if (null !== $data->getEmail()) {
            $user->setEmail($data->getEmail());
        }

        if (null !== $data->getPhone()) {
            $user->setPhone($data->getPhone());
        }

        $this->entityStorageService->flush();

        return $user;
    }
}