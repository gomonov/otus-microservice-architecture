<?php

namespace App\Application\Security;

use App\Application\User\Model\UserModelInterface;

interface PasswordHashServiceInterface
{
    public function isPasswordValid(UserModelInterface $user, string $password): bool;
}