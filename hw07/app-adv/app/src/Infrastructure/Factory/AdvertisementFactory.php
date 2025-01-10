<?php

namespace App\Infrastructure\Factory;

use App\Application\Advertisement\Factory\AdvertisementFactoryInterface;
use App\Application\Advertisement\Model\AdvertisementModelInterface;
use App\Infrastructure\Entity\Advertisement;

class AdvertisementFactory implements AdvertisementFactoryInterface
{
    public function create(): AdvertisementModelInterface
    {
        return new Advertisement();
    }
}