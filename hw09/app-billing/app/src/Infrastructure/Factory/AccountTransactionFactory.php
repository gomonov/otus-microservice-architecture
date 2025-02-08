<?php

namespace App\Infrastructure\Factory;

use App\Application\Account\Factory\AccountTransactionFactoryInterface;
use App\Application\Account\Model\AccountTransactionModelInterface;
use App\Infrastructure\Entity\AccountTransaction;

class AccountTransactionFactory implements AccountTransactionFactoryInterface
{
    public function create(): AccountTransactionModelInterface
    {
        return new AccountTransaction();
    }
}