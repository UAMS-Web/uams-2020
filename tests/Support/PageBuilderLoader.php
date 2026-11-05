<?php

declare(strict_types=1);

namespace Uams2020\Tests\Support;

use PHPUnit\Framework\Assert;

use function Brain\Monkey\Functions\when;
use function Brain\Monkey\setUp;
use function Brain\Monkey\tearDown;
use function Patchwork\redefine;
use function Patchwork\relay;

/** Loads declarations before tests can create mocks of the same theme functions. */
final class PageBuilderLoader
{
    public static array $hooks = [];

    private static bool $loaded = false;

    public static function load(): void
    {
        if (self::$loaded) {
            return;
        }

        when('function_exists')->alias(static fn ($name) => in_array($name, ['acf_add_options_page', 'acf_add_local_field_group'], true) ? false : relay());
        foreach (['add_action' => 'action', 'add_filter' => 'filter'] as $function => $type) {
            redefine($function, static function ($name, $callback, $priority = 10, $accepted = 1) use ($type) {
                self::$hooks[] = [$type, $name, $callback, $priority, $accepted];

                return relay();
            });
        }

        require dirname(__DIR__, 2).'/lib/meta-box.php';
        require dirname(__DIR__, 2).'/lib/blocks.php';
        require dirname(__DIR__, 2).'/lib/modules.php';
        require dirname(__DIR__, 2).'/lib/acf.php';
        Assert::assertCount(8, self::$hooks);
        self::$loaded = true;
        tearDown();
        setUp();
    }
}
