<?php

namespace App\Application\Advertisement\Model;

enum AdvertisementStatusEnum: int
{
    case PENDING = 1;
    case ACTIVE = 2;
}
