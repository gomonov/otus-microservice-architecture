<?php

namespace App\Application\User\UseCase;

use App\Application\Security\PasswordHashServiceInterface;
use App\Application\User\Exception\UserNotFoundException;
use App\Application\User\Model\UserModelInterface;
use App\Application\User\Repository\UserRepositoryInterface;
use App\Application\User\UseCase\Contract\UserLoginDataInterface;

readonly class UserLoginAction
{
    public function __construct(
        private UserRepositoryInterface      $userRepository,
        private PasswordHashServiceInterface $passwordHashService,
    ) {
    }

    /**
     * @throws UserNotFoundException
     */
    public function do(UserLoginDataInterface $data): UserModelInterface
    {
        $user = $this->userRepository->getByUsername($data->getUsername());
        if (null === $user) {
            throw new UserNotFoundException('User not found');
        }

        $isValid = $this->passwordHashService->isPasswordValid($user, $data->getPassword());
        if (false === $isValid) {
            throw new UserNotFoundException('Username or password incorrect');
        }

        return $user;
    }
}