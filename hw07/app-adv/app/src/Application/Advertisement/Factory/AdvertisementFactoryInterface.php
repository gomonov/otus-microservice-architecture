<?php

namespace App\Application\Advertisement\Factory;

use App\Application\Advertisement\Model\AdvertisementModelInterface;

interface AdvertisementFactoryInterface
{
    public function create(): AdvertisementModelInterface;
}