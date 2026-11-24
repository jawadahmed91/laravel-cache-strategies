<?php

declare(strict_types=1);

namespace JawadAhmed\CacheStrategies;

use Closure;

final class CachedProductFinder
{
    public function __construct(private readonly CacheAside $cache, private readonly Closure $databaseLookup) {}

    public function find(int $productId): array
    {
        return $this->cache->remember('products', $productId, 300, fn (): array => ($this->databaseLookup)($productId));
    }

    public function invalidate(int $productId): void
    {
        $this->cache->forget('products', $productId);
    }
}
