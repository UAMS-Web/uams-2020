<?php

declare(strict_types=1);

namespace Uams2020\Tests\Support;

use PHPUnit\Framework\Assert;

use function Brain\Monkey\Actions\expectRemoved;
use function Brain\Monkey\Functions\when;
use function Brain\Monkey\setUp;
use function Brain\Monkey\tearDown;
use function Patchwork\redefine;

/** Loads the theme under coverage, then clears its load-time mocks before each test. */
final class ThemeBootstrap
{
    private static bool $loaded = false;

    public static function load(): void
    {
        if (self::$loaded) {
            return;
        }

        setUp();

        when('plugin_dir_path')->justReturn(dirname(__DIR__, 2).'/');
        when('plugin_dir_url')->justReturn('https://example.test/wp-content/plugins/'.basename(dirname(__DIR__, 2)).'/');
        when('plugin_basename')->alias(static fn ($file): string => basename(dirname($file)).'/'.basename($file));
        when('is_multisite')->justReturn(false);
        when('get_site_option')->alias(static fn ($key, $default = false) => $default);
        when('get_option')->alias(static fn ($key, $default = false) => $default);
        when('update_option')->justReturn();
        when('register_activation_hook')->justReturn();
        when('register_deactivation_hook')->justReturn();
        when('register_uninstall_hook')->justReturn();
        when('load_plugin_textdomain')->justReturn();
        when('is_admin')->justReturn(false);
        when('wp_enqueue_script')->justReturn();
        when('wp_enqueue_style')->justReturn();
        when('wp_register_script')->justReturn();
        when('wp_register_style')->justReturn();
        when('wp_localize_script')->justReturn();
        when('esc_html')->returnArg();
        when('esc_attr')->returnArg();
        when('esc_url')->returnArg();
        when('__')->returnArg();
        when('_e')->justReturn();
        when('esc_html__')->returnArg();
        when('esc_attr__')->returnArg();
        when('get_template_directory')->justReturn(dirname(__DIR__, 2));
        when('get_stylesheet_directory')->justReturn(dirname(__DIR__, 2));
        when('get_template_directory_uri')->justReturn('https://example.test/wp-content/themes/'.basename(dirname(__DIR__, 2)));
        when('get_stylesheet_directory_uri')->justReturn('https://example.test/wp-content/themes/'.basename(dirname(__DIR__, 2)));
        when('trailingslashit')->alias(static fn ($value): string => rtrim((string) $value, '/\\').'/');
        when('load_theme_textdomain')->justReturn();
        when('add_theme_support')->justReturn();
        when('register_nav_menus')->justReturn();
        when('wp_get_theme')->justReturn(new class
        {
            public function get($key): string
            {
                return $key === 'Version' ? '0.0.0-test' : '';
            }
        });

        // Keep actual anonymous callbacks available for unit tests without reloading declarations.
        expectRemoved('genesis_before_header')->once()->with('genesis_skip_links', 5);
        redefine('add_action', ThemeHooks::capture('action'));
        redefine('add_filter', ThemeHooks::capture('filter'));

        if (is_readable(dirname(__DIR__, 2).'/functions.php')) {
            require_once dirname(__DIR__, 2).'/functions.php';
        }

        $expected = json_decode((string) file_get_contents(__DIR__.'/../Fixtures/theme-hook-registrations.json'), true, flags: JSON_THROW_ON_ERROR);
        $actual = array_map(static function (array $row): array {
            $row['callback'] = $row['callback'] instanceof \Closure ? 'Closure' : $row['callback'];

            return $row;
        }, ThemeHooks::$registrations);
        Assert::assertSame($expected, $actual);
        Assert::assertSame('0.0.0-test', CHILD_THEME_VERSION);
        foreach (['uams_get_site_info', 'uams_get_permalink', 'uams_site_title', 'uams_get_home_link', 'apStyleDate'] as $function) {
            Assert::assertSame(dirname(__DIR__, 2).'/functions.php', (new \ReflectionFunction($function))->getFileName());
        }

        self::$loaded = true;
        tearDown();
        setUp();

    }
}
