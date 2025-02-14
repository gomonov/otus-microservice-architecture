<?php

namespace App\Infrastructure\Factory;

use App\Application\Bonus\Factory\BonusFactoryInterface;
use App\Application\Bonus\Model\BonusModelInterface;
use App\Infrastructure\Entity\Bonus;

class BonusFactory implements BonusFactoryInterface
{
    public function create(): BonusModelInterface
    {
        return new Bonus();
    }
}