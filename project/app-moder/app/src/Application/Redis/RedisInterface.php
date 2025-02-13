<?php

namespace App\Application\Redis;

interface RedisInterface
{
    public function get(string $key): mixed;

    public function set(string $key, mixed $value, ?int $ttl = null): void;
}