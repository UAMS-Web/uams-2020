<?php

declare(strict_types=1);

namespace Uams2020\Tests\Support;

/** Query boundary for unit tests; integration tests use WordPress' actual registry. */
final class BlockRegistryDouble
{
    public static array $registered = [];

    public static function get_instance(): self
    {
        return new self;
    }

    public function get_registered(string $name): bool
    {
        return array_key_exists($name, self::$registered);
    }

    public function get_all_registered(): array
    {
        return self::$registered;
    }
}
