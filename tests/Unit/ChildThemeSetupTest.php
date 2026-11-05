<?php

declare(strict_types=1);

use Brain\Monkey\Filters;
use Brain\Monkey\Functions;

use function Brain\Monkey\Actions\expectAdded;
use function Brain\Monkey\Actions\expectRemoved;
use function Brain\Monkey\Functions\when;

it('loads the engine and child libraries and registers the current theme support', function (): void {
    $fixture = __DIR__.'/../Fixtures/genesis';
    define('CHILD_DIR', $fixture);
    define('CHILD_URL', 'https://example.test/child');
    $GLOBALS['uams2020_setup_includes'] = [];
    when('get_template_directory')->justReturn($fixture);
    when('get_stylesheet_directory')->justReturn('/child-theme');
    when('__')->returnArg();
    Functions\expect('glob')->once()->with(dirname(__DIR__, 2).'/lib/*.php')->andReturn([$fixture.'/child-library.php']);
    Functions\expect('load_child_theme_textdomain')->once()->with('uams-2020', '/child-theme/languages');
    foreach (['rsd_link', 'wlwmanifest_link', 'wp_generator', 'start_post_rel_link', 'index_rel_link', 'adjacent_posts_rel_link', 'wp_shortlink_wp_head', 'rest_output_link_wp_head', 'wp_oembed_add_discovery_links'] as $callback) {
        expectRemoved('wp_head')->once()->with($callback, 10);
    }

    expectRemoved('template_redirect')->once()->with('rest_output_link_header', 11);
    expectRemoved('genesis_after_content_sidebar_wrap')->once()->with('genesis_get_sidebar_alt');
    expectAdded('genesis_after_content')->once()->with('genesis_get_sidebar_alt');
    expectRemoved('genesis_entry_content')->once()->with('genesis_do_post_image', 8);
    expectAdded('genesis_entry_header')->once()->with('genesis_do_post_image', 0);
    Filters\expectAdded('use_default_gallery_style')->once()->with('__return_false');
    Filters\expectAdded('widget_text')->once()->with('shortcode_unautop');
    Filters\expectAdded('widget_text')->once()->with('do_shortcode');
    Filters\expectAdded('image_size_names_choose')->once()->with('uams_custom_add_image_size_names');
    Functions\expect('add_post_type_support')->once()->with('page', 'excerpt');
    Functions\expect('unregister_sidebar')->once()->with('header-right');
    $supports = [];
    when('add_theme_support')->alias(static function (...$args) use (&$supports): void {
        $supports[] = $args;
    });
    $sizes = [];
    when('add_image_size')->alias(static function (...$args) use (&$sizes): void {
        $sizes[] = $args;
    });
    try {
        uamswp_childtheme_setup();
        expect($GLOBALS['uams2020_setup_includes'])->toBe(['engine', 'activation', 'library'])
            ->and($supports)->toBe([
                ['html5', ['search-form', 'comment-form', 'comment-list']],
                ['genesis-menus', ['primary' => 'Primary Navigation Menu']],
                ['genesis-responsive-viewport'], ['genesis-footer-widgets', 1],
                ['custom-logo', ['flex-width' => true, 'flex-height' => true]],
                ['genesis-structural-wraps', []], ['genesis-connect-woocommerce'], ['responsive-embeds'], ['align-wide'],
                ['genesis-accessibility', ['404-page', 'drop-down-menu', 'headings', 'rems', 'search-form', 'skip-links']],
            ])
            ->and($sizes)->toBe([
                ['bootstrap-featured', 730, 0, true], ['aspect-16-9', 1024, 576, true], ['aspect-16-9-small', 512, 288, true],
                ['aspect-8-3', 1024, 384, true], ['aspect-4-3', 1024, 768, true], ['aspect-2-1', 1024, 512, true],
                ['aspect-1-1', 1024, 1024, true], ['hero-tablet', 455, 256, true], ['portrait-3-4', 243, 324, true],
                ['content-image-side', 299, 9999], ['content-image-center', 630, 9999], ['content-image-wide', 1020, 9999], ['content-image-full', 1920, 9999],
            ])
            ->and(UAMSWP_THEME_NAME)->toBe('Bootstrap for Genesis')
            ->and(UAMSWP_THEME_URL)->toBe('http://webdevsuperfast.github.io/')
            ->and(UAMSWP_THEME_LIB)->toBe($fixture.'/lib/')
            ->and(UAMSWP_THEME_MODULES)->toBe($fixture.'/lib/modules/')
            ->and(UAMSWP_THEME_LIB_URL)->toBe('https://example.test/child/lib/')
            ->and(UAMSWP_THEME_IMAGES)->toBe('https://example.test/child/images/')
            ->and(UAMSWP_THEME_JS)->toBe('https://example.test/child/assets/js/')
            ->and(UAMSWP_THEME_CSS)->toBe('https://example.test/child/assets/css/');
    } finally {
        unset($GLOBALS['uams2020_setup_includes']);
    }
});
