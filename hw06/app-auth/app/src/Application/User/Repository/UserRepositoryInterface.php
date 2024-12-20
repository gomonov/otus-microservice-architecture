<?php

namespace App\Application\User\Repository;

use App\Application\User\Model\UserModelInterface;

interface UserRepositoryInterface
{
    public function getByUsername(string $username): ?UserModelInterface;
}