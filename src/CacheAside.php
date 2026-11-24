<?php

declare(strict_types=1);

namespace JawadAhmed\CacheStrategies;

use Closure;
use DateInterval;
use DateTimeInterface;
use Illuminate\Contracts\Cache\Repository;

final class CacheAside
{
    public function __construct(private readonly Repository $cache) {}

    public function remember(string $namespace, string|int $id, DateTimeInterface|DateInterval|int|null $ttl, Closure $loader, int $version = 1): mixed
    {
        return $this->cache->remember($this->key($namespace, $id, $version), $ttl, $loader);
    }

    public function refresh(string $namespace, string|int $id, DateTimeInterface|DateInterval|int|null $ttl, Closure $loader, int $version = 1): mixed
    {
        $value = $loader();
        $this->cache->put($this->key($namespace, $id, $version), $value, $ttl);
        return $value;
    }

    public function forget(string $namespace, string|int $id, int $version = 1): bool
    {
        return $this->cache->forget($this->key($namespace, $id, $version));
    }

    public function key(string $namespace, string|int $id, int $version = 1): string
    {
        return sprintf('%s:v%d:%s', trim($namespace, ':'), $version, (string) $id);
    }
}
