<?php

namespace App\Infrastructure\Uuid;

use App\Application\Uuid\UuidGeneratorInterface;
use Ramsey\Uuid\Uuid;

class Uuid4 implements UuidGeneratorInterface
{
    public function generate(): string
    {
        return Uuid::uuid4()->toString();
    }
}