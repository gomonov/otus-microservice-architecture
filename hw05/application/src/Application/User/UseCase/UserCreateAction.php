<?php

namespace App\Application\User\UseCase;

use App\Application\EntityStorage\Exception\EntityStorageException;
use App\Application\User\Model\UserModelInterface;
use App\Application\User\UseCase\Contract\UserCreateDataInterface;
use App\Infrastructure\Factory\UserFactory;
use App\Application\EntityStorage\EntityStorageServiceInterface;

readonly class UserCreateAction
{
    public function __construct(
        private UserFactory                   $userFactory,
        private EntityStorageServiceInterface $entityStorageService,
    ) {
    }

    /**
     * @throws EntityStorageException
     */
    public function do(UserCreateDataInterface $data): UserModelInterface
    {
        $user = $this->userFactory->create();
        $user->setUsername($data->getUsername());
        $user->setFirstName($data->getFirstName());
        $user->setLastName($data->getLastName());
        $user->setEmail($data->getEmail());
        $user->setPhone($data->getPhone());

        $this->entityStorageService->persist($user);
        $this->entityStorageService->flush();

        return $user;
    }
}