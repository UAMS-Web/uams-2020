<?php

declare(strict_types=1);

use Brain\Monkey\Functions;

use function Brain\Monkey\Filters\expectApplied;
use function Brain\Monkey\Functions\when;

beforeEach(function (): void {
    when('genesis_a11y')->justReturn(true);
    when('genesis_skiplinks_markup')->justReturn();
    when('genesis_site_layout')->justReturn('full-width-content');
    when('current_theme_supports')->justReturn(false);
    when('esc_html__')->returnArg();
    when('esc_url')->returnArg();
});

it('requires both primary-menu support and an assigned menu before adding its skip link', function (bool $supported, bool $assigned, string $navigation): void {
    Functions\expect('genesis_nav_menu_supported')->once()->with('primary')->andReturn($supported);
    Functions\expect('has_nav_menu')->times($supported ? 1 : 0)->with('primary')->andReturn($assigned);
    ob_start();
    uamswp_skip_links();
    expect(ob_get_clean())->toBe('<nav aria-label="Skip links"><ul class="genesis-skip-link">'.$navigation.'<li><a href="#genesis-content" class="screen-reader-shortcut"> Skip to main content</a></li></ul></nav>');
})->with([
    [false, true, ''],
    [true, false, ''],
    [true, true, '<li><a href="#genesis-nav-primary" class="screen-reader-shortcut"> Skip to primary navigation</a></li>'],
]);

it('casts a scalar skip-link filter result before rendering it', function (): void {
    when('genesis_nav_menu_supported')->justReturn(false);
    expectApplied('genesis_skip_links_output')->once()->with(['genesis-content' => 'Skip to main content'])->andReturn('Custom destination');
    ob_start();
    uamswp_skip_links();
    expect(ob_get_clean())->toBe('<nav aria-label="Skip links"><ul class="genesis-skip-link"><li><a href="#0" class="screen-reader-shortcut"> Custom destination</a></li></ul></nav>');
});
