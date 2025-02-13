<?php

namespace App\Infrastructure\Factory;

use App\Application\Bonus\Factory\BonusTransactionFactoryInterface;
use App\Application\Bonus\Model\BonusTransactionModelInterface;
use App\Infrastructure\Entity\BonusTransaction;

class BonusTransactionFactory implements BonusTransactionFactoryInterface
{
    public function create(): BonusTransactionModelInterface
    {
        return new BonusTransaction();
    }
}