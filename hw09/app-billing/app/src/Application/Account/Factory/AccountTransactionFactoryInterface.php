<?php

namespace App\Application\Account\Factory;

use App\Application\Account\Model\AccountTransactionModelInterface;

interface AccountTransactionFactoryInterface
{
    public function create(): AccountTransactionModelInterface;
}