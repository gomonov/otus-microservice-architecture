<?php

namespace App\Application\User\Repository;

use App\Application\User\Model\UserModelInterface;

interface UserRepositoryInterface
{
    public function getById(int $userId): ?UserModelInterface;

    public function getByUsername(string $username): ?UserModelInterface;

    public function getByEmail(string $email): ?UserModelInterface;
}