<?php

namespace App\Application\Bonus\Factory;

use App\Application\Bonus\Model\BonusModelInterface;

interface BonusFactoryInterface
{
    public function create(): BonusModelInterface;
}