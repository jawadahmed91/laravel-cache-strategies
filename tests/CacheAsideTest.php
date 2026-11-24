<?php

declare(strict_types=1);

namespace Tests;

use Illuminate\Cache\ArrayStore;
use Illuminate\Cache\Repository;
use JawadAhmed\CacheStrategies\CacheAside;
use JawadAhmed\CacheStrategies\CachedProductFinder;
use PHPUnit\Framework\TestCase;

final class CacheAsideTest extends TestCase
{
    public function test_it_caches_and_invalidates_database_results(): void
    {
        $calls = 0;
        $finder = new CachedProductFinder(new CacheAside(new Repository(new ArrayStore())), function (int $id) use (&$calls): array {
            $calls++;
            return ['id' => $id, 'revision' => $calls];
        });
        self::assertSame(1, $finder->find(7)['revision']);
        self::assertSame(1, $finder->find(7)['revision']);
        self::assertSame(1, $calls);
        $finder->invalidate(7);
        self::assertSame(2, $finder->find(7)['revision']);
    }

    public function test_keys_are_versioned(): void
    {
        $cache = new CacheAside(new Repository(new ArrayStore()));
        self::assertSame('catalog:v1:15', $cache->key('catalog', 15));
        self::assertSame('catalog:v2:15', $cache->key('catalog', 15, 2));
    }
}
