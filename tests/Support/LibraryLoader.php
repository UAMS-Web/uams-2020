<?php

declare(strict_types=1);

namespace Uams2020\Tests\Support;

use PHPUnit\Framework\Assert;

use function Brain\Monkey\Functions\when;
use function Brain\Monkey\setUp;
use function Brain\Monkey\tearDown;
use function Patchwork\redefine;
use function Patchwork\relay;

/** Keeps original library callbacks available after load-time hooks are cleared. */
final class LibraryLoader
{
    public static array $hooks = [];

    public static array $loadCalls = [];

    private static bool $loaded = false;

    public static function load(): void
    {
        if (self::$loaded) {
            return;
        }

        require_once __DIR__.'/LibraryCoreDoubles.php';
        foreach (['add_action' => 'action', 'add_filter' => 'filter', 'remove_action' => 'remove_action', 'remove_filter' => 'remove_filter'] as $function => $type) {
            redefine($function, static function ($name, $callback, $priority = 10, $accepted = 1) use ($type) {
                self::$hooks[] = [$type, $name, $callback, $priority, $accepted];

                return relay();
            });
        }

        foreach (['genesis_unregister_layout', 'genesis_set_default_layout', 'unregister_sidebar', 'remove_theme_support', 'add_shortcode'] as $function) {
            when($function)->alias(static function (...$args) use ($function): void {
                self::$loadCalls[] = [$function, $args];
            });
        }

        when('uams_get_site_info')->justReturn(['site' => 'institute', 'subsite' => 'eye', 'department' => 'main']);
        foreach (['a11y', 'bootstrap', 'breadcrumb', 'comment', 'customizer', 'pagination', 'search', 'search-tray', 'site-container-outer', 'geo', 'footer-widgets', 'extras', 'favicon', 'footer', 'header', 'misc', 'nav', 'page', 'quicklinks', 'scripts', 'tgm', 'users', 'media-credit', 'widget-areas', 'widgets', 'modules/class-wp-bootstrap-pagewalker'] as $file) {
            require dirname(__DIR__, 2).'/lib/'.$file.'.php';
        }

        $actualHooks = array_map(static function (array $row): array {
            $row[2] = $row[2] instanceof \Closure ? 'Closure' : $row[2];

            return $row;
        }, self::$hooks);
        $expected = json_decode((string) file_get_contents(__DIR__.'/../Fixtures/library-hook-registrations.json'), true, flags: JSON_THROW_ON_ERROR);
        Assert::assertSame($expected, ['hooks' => $actualHooks, 'calls' => self::$loadCalls]);
        Assert::assertTrue(function_exists('quicklinks_menu'));
        $regions = [];
        foreach (json_decode((string) file_get_contents(__DIR__.'/../Fixtures/library-region-cities.json'), true, flags: JSON_THROW_ON_ERROR) as [$region, $city]) {
            $regions[$region][] = $city;
        }

        foreach ($regions as $region => $cities) {
            Assert::assertTrue($cities === ($GLOBALS['region_'.$region] ?? null), 'Configured city list changed: '.$region);
        }

        self::$loaded = true;
        tearDown();
        setUp();
    }

    public static function callback(string $name): \Closure
    {
        $matches = array_filter(self::$hooks, static fn (array $row): bool => $row[1] === $name && $row[2] instanceof \Closure);
        Assert::assertCount(1, $matches);

        return array_values($matches)[0][2];
    }
}
