<?php

namespace App\Application\Bonus\Factory;

use App\Application\Bonus\Model\BonusTransactionModelInterface;

interface BonusTransactionFactoryInterface
{
    public function create(): BonusTransactionModelInterface;
}