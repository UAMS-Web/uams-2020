<?php

/**
 * Unit bootstrap for the theme (Pest 5).
 *
 * The Unit suite runs with no WordPress: WordPress functions are mocked per
 * test with Brain Monkey. The Integration suite does not come through here; it
 * has its own Composer setup and bootstrap under tests/Integration.
 */

declare(strict_types=1);

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
Brain\Monkey\setUp();

Brain\Monkey\Functions\when('plugin_dir_path')->justReturn(dirname(__DIR__).'/');
Brain\Monkey\Functions\when('plugin_dir_url')->justReturn('https://example.test/wp-content/plugins/'.basename(dirname(__DIR__)).'/');
Brain\Monkey\Functions\when('plugin_basename')->alias(static function ($file) {
    return basename(dirname($file)).'/'.basename($file);
});
Brain\Monkey\Functions\when('is_multisite')->justReturn(false);
Brain\Monkey\Functions\when('get_site_option')->alias(static function ($key, $default = false) {
    return $default;
});
Brain\Monkey\Functions\when('get_option')->alias(static function ($key, $default = false) {
    return $default;
});
Brain\Monkey\Functions\when('update_option')->justReturn(null);
Brain\Monkey\Functions\when('register_activation_hook')->justReturn(null);
Brain\Monkey\Functions\when('register_deactivation_hook')->justReturn(null);
Brain\Monkey\Functions\when('register_uninstall_hook')->justReturn(null);
Brain\Monkey\Functions\when('load_plugin_textdomain')->justReturn(null);
Brain\Monkey\Functions\when('is_admin')->justReturn(false);
Brain\Monkey\Functions\when('wp_enqueue_script')->justReturn(null);
Brain\Monkey\Functions\when('wp_enqueue_style')->justReturn(null);
Brain\Monkey\Functions\when('wp_register_script')->justReturn(null);
Brain\Monkey\Functions\when('wp_register_style')->justReturn(null);
Brain\Monkey\Functions\when('wp_localize_script')->justReturn(null);
Brain\Monkey\Functions\when('esc_html')->returnArg();
Brain\Monkey\Functions\when('esc_attr')->returnArg();
Brain\Monkey\Functions\when('esc_url')->returnArg();
Brain\Monkey\Functions\when('__')->returnArg();
Brain\Monkey\Functions\when('_e')->justReturn(null);
Brain\Monkey\Functions\when('esc_html__')->returnArg();
Brain\Monkey\Functions\when('esc_attr__')->returnArg();
Brain\Monkey\Functions\when('get_template_directory')->justReturn(dirname(__DIR__));
Brain\Monkey\Functions\when('get_stylesheet_directory')->justReturn(dirname(__DIR__));
Brain\Monkey\Functions\when('get_template_directory_uri')->justReturn('https://example.test/wp-content/themes/'.basename(dirname(__DIR__)));
Brain\Monkey\Functions\when('get_stylesheet_directory_uri')->justReturn('https://example.test/wp-content/themes/'.basename(dirname(__DIR__)));
Brain\Monkey\Functions\when('trailingslashit')->alias(static function ($value) {
    return rtrim((string) $value, '/\\').'/';
});
Brain\Monkey\Functions\when('load_theme_textdomain')->justReturn(null);
Brain\Monkey\Functions\when('add_theme_support')->justReturn(null);
Brain\Monkey\Functions\when('register_nav_menus')->justReturn(null);
Brain\Monkey\Functions\when('wp_get_theme')->justReturn(new class {
    public function get($key)
    {
        return $key === 'Version' ? '0.0.0-test' : '';
    }
});

if (is_readable(dirname(__DIR__).'/functions.php')) {
    require_once dirname(__DIR__).'/functions.php';
}

Brain\Monkey\tearDown();

// cspell:ignore ABSPATH stylesheet
