<?php

namespace App\Application\User\Repository;

use App\Application\User\Model\UserModelInterface;

interface UserRepositoryInterface
{
    public function getById(int $userId): ?UserModelInterface;
}