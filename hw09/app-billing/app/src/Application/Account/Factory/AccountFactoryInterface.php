<?php

namespace App\Application\Account\Factory;

use App\Application\Account\Model\AccountModelInterface;

interface AccountFactoryInterface
{
    public function create(): AccountModelInterface;
}