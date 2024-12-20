<?php

namespace App\Infrastructure\Security;

use App\Application\Security\PasswordHashServiceInterface;
use App\Application\User\Model\UserModelInterface;
use App\Infrastructure\Entity\User;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

readonly class PasswordHashService implements PasswordHashServiceInterface
{
    public function __construct(
        private UserPasswordHasherInterface $userPasswordHasher,
    ) {}

    public function isPasswordValid(UserModelInterface $user, string $password): bool
    {
        /** @var User $user */
        return $this->userPasswordHasher->isPasswordValid($user, $password);
    }
}