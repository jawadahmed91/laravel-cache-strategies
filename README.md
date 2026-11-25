# Laravel Cache Strategies


A focused Laravel 13 example of the cache-aside pattern: read from cache, load from the database on a miss, and invalidate cached data after a write.


## Features


- Cache-aside reads through Laravel's cache repository contract
- Explicit invalidation after writes
- Forced refreshes for administrative workflows
- Versioned keys for safe payload schema changes
- PHPUnit coverage


## Install and test


Run composer install and composer test.


Use a shared store such as Redis when multiple application servers must see the same values. Invalidate only after the database transaction succeeds.


## Add this to an existing Laravel project

1. Copy `src/CacheAside.php` and `src/CachedProductFinder.php` to `app/Support/Caching/`.
2. Change both namespaces from `JawadAhmed\CacheStrategies` to `App\Support\Caching`.
3. Configure your cache store in `.env`. Use Redis or another shared store when the application runs on multiple servers.
4. Construct `CacheAside` with Laravel's cache repository and bind the product finder in a service provider.

```php
$this->app->singleton(CachedProductFinder::class, fn ($app) => new CachedProductFinder(
    new CacheAside($app->make('cache.store')),
    fn (int $id): array => Product::query()->findOrFail($id)->toArray(),
));
```

5. Inject `CachedProductFinder` into controllers or services and call `find($productId)` for read operations.
6. After a successful product create, update, or delete transaction, call `invalidate($productId)` so the next read reloads fresh data.
7. Choose a TTL based on acceptable staleness. Increase the key version when the cached payload structure changes incompatibly.
8. Copy `tests/CacheAsideTest.php` into your test suite, update its namespace and imports, and run `php artisan test`.
9. Verify a cache miss calls the database, a second read uses the cache, and invalidation causes another database lookup.

## Requirements


- PHP 8.3+
- Laravel components 13.x


## License


MIT

