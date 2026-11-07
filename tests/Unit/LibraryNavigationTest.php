<?php

declare(strict_types=1);

use Brain\Monkey\Functions;

use function Brain\Monkey\Actions\expectAdded;
use function Brain\Monkey\Actions\expectRemoved;
use function Brain\Monkey\Filters\expectApplied;
use function Brain\Monkey\Functions\when;
use function Patchwork\relay;

/**
 * lib/nav.php: EmptyStringToNotEmpty on parentsection is equivalent: each loop iteration overwrites
 * it before reading, and an empty parent list never reads it.
 */
it('finds the nearest marked subsection unless this is a search request', function ($marked, array $parents, array $meta, bool $search, $expected): void {
    when('get_the_id')->justReturn(41);
    $visited = [];
    when('get_post_meta')->alias(function ($id, $key, $single) use ($marked, $meta, &$visited) {
        expect($key)->toBe('page_subsection')->and($single)->toBeTrue();
        $visited[] = $id;

        return $id === 41 ? $marked : ($meta[$id] ?? false);
    });
    Functions\expect('get_post_ancestors')->times($marked ? 0 : 1)->with(41)->andReturn($parents);
    Functions\expect('is_search')->once()->andReturn($search);
    expect(uamswp_nav_subsection())->toBe($expected);
    expect($visited)->toBe($marked ? [41] : ($expected === 7 || ($search && ($meta[7] ?? false)) ? [41, 9, 7] : [41, ...$parents]));
})->with([
    [true, [], [], false, 41], [true, [], [], true, false], [false, [], [], false, false],
    [false, [9, 7, 3], [7 => true, 3 => true], false, 7],
    [false, [9, 7, 3], [7 => true, 3 => true], true, false],
    [false, [9, 7], [], false, false],
]);

it('removes primary navigation only for a subsection or missing primary menu', function ($subsection, bool $menu): void {
    Functions\expect('uamswp_nav_subsection')->once()->andReturn($subsection);
    Functions\expect('has_nav_menu')->times($subsection ? 0 : 1)->with('primary')->andReturn($menu);
    expectRemoved('genesis_after_header')->times($subsection || ! $menu ? 1 : 0)->with('genesis_do_nav');
    remove_primary_nav();
})->with([[7, true], [7, false], [false, true], [false, false]]);

/**
 * lib/nav.php: ConcatEqualToEqual on the first navigation wrapper append is equivalent because
 * output is a fresh empty local string.
 */
it('wraps only primary menus and inserts filtered extra content when enabled', function (string $location, $extra, string $expected): void {
    Functions\expect('sanitize_html_class')->times($location === 'primary' ? 1 : 0)->with('-primary')->andReturn('-SAFE');
    Functions\expect('get_theme_mod')->times($location === 'primary' ? 1 : 0)->with('navextra', false)->andReturn($extra);
    Functions\expect('uamswp_navbar_content_markup')->times($location === 'primary' && $extra == true ? 1 : 0)->andReturn('EXTRA');
    if ($location === 'primary' && $extra == true) {
        expectApplied('uamswp_navbar_content')->once()->with('EXTRA')->andReturn('FILTERED');
    }

    expect(uamswp_nav_menu_markup_filter('MENU', (object) ['theme_location' => $location]))->toBe($expected);
})->with([
    ['secondary', true, 'MENU'],
    ['primary', false, '<nav class="site-nav navbar navbar-expand-sm" aria-label="Primary Navigation"><div class="collapse navbar-collapse inner-container" id="genesis-nav-SAFE">MENU</div></nav>'],
    ['primary', 'search', '<nav class="site-nav navbar navbar-expand-sm" aria-label="Primary Navigation"><div class="collapse navbar-collapse inner-container" id="genesis-nav-SAFE">FILTEREDMENU</div></nav>'],
]);

/**
 * lib/nav.php: the three ConcatEqualToEqual mutations on the first append in each switch arm are
 * equivalent. Each arm starts with the same empty local output and terminates with break.
 */
it('pins search, date, empty and unknown navigation-extra choices', function ($choice, string $expected): void {
    Functions\expect('get_home_url')->once()->withNoArgs()->andReturn('https://home.test/');
    Functions\expect('get_theme_mod')->once()->with('navextra', 'search')->andReturn($choice);
    Functions\expect('get_option')->times($choice === 'date' ? 1 : 0)->with('date_format')->andReturn('Y-m-d');
    Functions\expect('date_i18n')->times($choice === 'date' ? 1 : 0)->with('Y-m-d')->andReturn('2026-11-05');
    expect(uamswp_navbar_content_markup())->toBe($expected);
})->with([
    ['search', '<form class="form-inline float-lg-right" method="get" action="https://home.test/" role="search"><input class="form-control mr-sm-2" type="text" placeholder="Search" name="s"><button class="btn btn-outline-success" type="submit">Search</button></form>'],
    ['unknown', '<form class="form-inline float-lg-right" method="get" action="https://home.test/" role="search"><input class="form-control mr-sm-2" type="text" placeholder="Search" name="s"><button class="btn btn-outline-success" type="submit">Search</button></form>'],
    ['date', '<p class="navbar-text navbar-right mb-0">2026-11-05</p>'], ['', ''],
]);

it('returns only the supplied navigation body', function (): void {
    expect(uamswp_override_do_nav('ignored', '<ul>MENU</ul>', ['ignored']))->toBe('<ul>MENU</ul>');
});

it('uses SEO breadcrumbs when available and registers Genesis otherwise', function (bool $available): void {
    if ($available) {
        Functions\expect('seopress_display_breadcrumbs')->once()->withNoArgs();
    }

    when('function_exists')->alias(static fn ($name) => $name === 'seopress_display_breadcrumbs' ? $available : relay());
    expectAdded('genesis_after_header')->times($available ? 0 : 1)->with('genesis_do_breadcrumbs');
    sp_breadcrumb_after_header();
})->with([true, false]);
