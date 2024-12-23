<?php

namespace App\Application\User\UseCase;

use App\Application\User\Exception\UserNotFoundException;
use App\Application\User\Model\UserModelInterface;
use App\Application\User\Repository\UserRepositoryInterface;

readonly class UserGetAction
{
    public function __construct(
        private UserRepositoryInterface $userRepository,
    ) {
    }

    /**
     * @throws UserNotFoundException
     */
    public function do(int $userId): UserModelInterface
    {
        $user = $this->userRepository->getById($userId);

        if (is_null($user)) {
            throw new UserNotFoundException('Пользователь не найден');
        }

        return $user;
    }
}