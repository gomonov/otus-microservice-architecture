<?php

namespace App\Application\User\Factory;


use App\Application\User\Model\UserModelInterface;

interface UserFactoryInterface
{
    public function create(): UserModelInterface;
}