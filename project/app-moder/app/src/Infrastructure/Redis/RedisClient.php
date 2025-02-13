<?php

namespace App\Infrastructure\Redis;

use App\Application\Redis\RedisInterface;
use Redis;

readonly class RedisClient implements RedisInterface
{
    public function __construct(private Redis $redis)
    {
    }

    public function get(string $key): mixed
    {
        return $this->redis->get($key);
    }

    public function set(string $key, mixed $value, ?int $ttl = null): void
    {
        if (null === $ttl) {
            $this->redis->set($key, $value);
        } else {
            $this->redis->set($key, $value, $ttl);
        }
    }
}