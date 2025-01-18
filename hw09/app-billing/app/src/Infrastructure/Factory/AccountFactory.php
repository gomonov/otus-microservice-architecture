<?php

namespace App\Infrastructure\Factory;

use App\Application\Account\Factory\AccountFactoryInterface;
use App\Application\Account\Model\AccountModelInterface;
use App\Infrastructure\Entity\Account;

class AccountFactory implements AccountFactoryInterface
{
    public function create(): AccountModelInterface
    {
        return new Account();
    }
}