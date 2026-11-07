<?php

declare(strict_types=1);

namespace Uams2020\Tests\Support;

use LogicException;

use function Patchwork\relay;

/** Retains the theme's real callbacks after the bootstrap's Monkey session ends. */
final class ThemeHooks
{
    public static array $registrations = [];

    public static function capture(string $type): \Closure
    {
        return static function ($hook, $callback, $priority = 10, $acceptedArgs = 1) use ($type) {
            self::$registrations[] = ['type' => $type, 'hook' => $hook, 'callback' => $callback, 'priority' => $priority, 'acceptedArgs' => $acceptedArgs];

            return relay();
        };
    }

    public static function callback(string $type, string $hook, int $priority = 10): callable
    {
        $matches = array_values(array_filter(self::$registrations, static fn (array $item): bool => $item['type'] === $type && $item['hook'] === $hook && $item['priority'] === $priority));
        if (count($matches) !== 1 || ! is_callable($matches[0]['callback'])) {
            throw new LogicException('Expected exactly one callable for '.$type.' '.$hook.' at '.$priority);
        }

        return $matches[0]['callback'];
    }
}
