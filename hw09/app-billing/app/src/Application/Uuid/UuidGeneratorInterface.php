<?php

namespace App\Application\Uuid;

interface UuidGeneratorInterface
{
    public function generate(): string;
}