<?php

namespace App\Application\Security;

use App\Application\User\Model\UserModelInterface;

interface PasswordHashServiceInterface
{
    public function hashPassword(UserModelInterface $user, string $password): string;
}