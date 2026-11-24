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

## Requirements

- PHP 8.3+
- Laravel components 13.x

## License

MIT
