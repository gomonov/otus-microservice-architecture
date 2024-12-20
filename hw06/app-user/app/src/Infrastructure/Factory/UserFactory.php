<?php

namespace App\Infrastructure\Factory;

use App\Application\User\Factory\UserFactoryInterface;
use App\Application\User\Model\UserModelInterface;
use App\Infrastructure\Entity\User;

class UserFactory implements UserFactoryInterface
{

    public function create(): UserModelInterface
    {
        return new User();
    }
}