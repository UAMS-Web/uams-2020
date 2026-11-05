<?php

/**
 * Unit bootstrap for the theme (Pest 5).
 *
 * The Unit suite runs with no WordPress: WordPress functions are mocked per
 * test with Brain Monkey. The Integration suite does not come through here; it
 * has its own Composer setup and bootstrap under tests/Integration.
 */

declare(strict_types=1);

use Uams2020\Tests\Support\ThemeHooks;

use function Brain\Monkey\Functions\when;
use function Brain\Monkey\setUp;
use function Brain\Monkey\tearDown;
use function Patchwork\redefine;

require_once __DIR__.'/../vendor/autoload.php';

/*
 * Patchwork first: it can only redefine functions declared in files loaded
 * after it, so loading it before functions.php is what lets a test mock one of
 * the theme's own functions, not only WordPress'.
 */
require_once __DIR__.'/../vendor/antecedent/patchwork/Patchwork.php';

if (! defined('ABSPATH')) {
    define('ABSPATH', sys_get_temp_dir().'/uams_2020-tests-no-wordpress/');
}

/*
 * functions.php registers hooks and calls WordPress at file scope, so it is
 * loaded inside one Brain Monkey session with what it calls at load time
 * stubbed. The functions it defines outlive the session; the hooks it
 * registered are discarded by tearDown(), so every test starts clean, and hook
 * registration is asserted in the Integration suite. Do not define permanent
 * WordPress function stubs in this file: Patchwork treats this bootstrap as too
 * early and Brain Monkey cannot redefine them in tests (DefinedTooEarly).
 */
setUp();

when('plugin_dir_path')->justReturn(__DIR__.'/../');
when('plugin_dir_url')->justReturn('https://example.test/wp-content/plugins/'.basename(dirname(__DIR__)).'/');
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
when('get_template_directory')->justReturn(dirname(__DIR__));
when('get_stylesheet_directory')->justReturn(dirname(__DIR__));
when('get_template_directory_uri')->justReturn('https://example.test/wp-content/themes/'.basename(dirname(__DIR__)));
when('get_stylesheet_directory_uri')->justReturn('https://example.test/wp-content/themes/'.basename(dirname(__DIR__)));
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
redefine('add_action', ThemeHooks::capture('action'));
redefine('add_filter', ThemeHooks::capture('filter'));

if (is_readable(__DIR__.'/../functions.php')) {
    require_once __DIR__.'/../functions.php';
}

tearDown();

// cspell:ignore ABSPATH stylesheet
