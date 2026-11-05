<?php

declare(strict_types=1);

use Brain\Monkey\Functions;
use Uams2020\Tests\Support\LibraryBrandingScenario;
use Uams2020\Tests\Support\LibraryWarnings;

use function Brain\Monkey\Functions\when;

it('pins which sites get custom quick-link menus', function (string $site, string $subsite, bool $expected): void {
    when('uams_get_site_info')->justReturn(['site' => $site, 'subsite' => $subsite]);
    expect(site_custom_quicklinks())->toBe($expected);
    Functions\expect('__')->times($expected ? 1 : 0)->with('Quick Links Menu')->andReturn('Translated menu');
    Functions\expect('register_nav_menu')->times($expected ? 1 : 0)->with('quick-links', 'Translated menu');
    register_quicklinks_menu();
})->with([
    ['institute', 'eye', true], ['institute', 'main', true], ['uamshealth', 'main', true], ['uamshealth', 'dept', false],
    ['inside', 'main', true], ['inside', 'dept', false], ['uams', 'main', true], ['uams', 'medicine', false], ['other', 'main', false],
]);

it('uses the selected remote menu endpoint and stops on HTTP errors', function (string $site, string $url): void {
    when('uams_get_site_info')->justReturn(['site' => $site]);
    $request = ['response' => 'error'];
    Functions\expect('wp_remote_get')->once()->with($url)->andReturn($request);
    when('is_wp_error')->justReturn(true);
    Functions\expect('wp_remote_retrieve_body')->never();
    ob_start();
    $result = uamswp_request_quicklinks();
    expect(ob_get_clean())->toBe('')->and($result)->toBeFalse();
})->with([
    ['uams', 'https://web.uams.edu/wp-json/menus/v2/quicklinks/'],
    ['institute', 'https://web.uams.edu/wp-json/menus/v2/quicklinks/'],
    ['uamshealth', 'https://uamshealth.com/wp-json/menus/v2/quicklinks/'],
    ['inside', 'https://inside.uams.edu/wp-json/menus/v2/quicklinks/'],
]);

it('renders remote link classes and targets while preserving the current empty response behavior', function (string $body, string $expected): void {
    when('uams_get_site_info')->justReturn(['site' => 'uams']);
    Functions\expect('wp_remote_get')->once()->with('https://web.uams.edu/wp-json/menus/v2/quicklinks/')->andReturn(['response' => 'ok']);
    when('is_wp_error')->justReturn(false);
    Functions\expect('wp_remote_retrieve_body')->once()->with(['response' => 'ok'])->andReturn($body);
    when('esc_url')->alias(static fn ($url): string => 'SAFE:'.$url);
    ob_start();
    $result = uamswp_request_quicklinks();
    expect(ob_get_clean())->toBe($expected)->and($result)->toBeNull();
})->with([
    ['[]', ''], ['null', ''], ['invalid json', ''],
    ['[{"title":"First","url":"/a","classes":["fas","fa-info"],"target":"_blank"},{"title":"Second","url":"/b","classes":[],"target":""}]', '<ul class="list-unstyled links links-large"><li><a href="SAFE:/a" target="_blank"><span class="fas fa-info fa-fw"></span>First</a></li><li><a href="SAFE:/b">Second</a></li></ul>'],
]);

it('returns only top-level quick-link data in menu order', function ($menu, $items, array $expected): void {
    Functions\expect('get_nav_menu_locations')->once()->andReturn(['quick-links' => 19]);
    Functions\expect('wp_get_nav_menu_object')->once()->with(19)->andReturn($menu);
    Functions\expect('wp_get_nav_menu_items')->times($menu ? 1 : 0)->with(29)->andReturn($items);
    expect(quicklinks_menu('unused'))->toBe($expected);
})->with([
    [false, false, []], [(object) ['term_id' => 29], false, []],
    [(object) ['term_id' => 29], [
        (object) ['ID' => 3, 'menu_item_parent' => '0', 'title' => 'First', 'url' => '/a', 'classes' => ['icon'], 'target' => '_blank', 'attr_title' => 'Link title'],
        (object) ['ID' => 4, 'menu_item_parent' => 3],
        (object) ['ID' => 5, 'menu_item_parent' => 0, 'title' => 'Second', 'url' => '/b', 'classes' => [], 'target' => '', 'attr_title' => ''],
    ], [
        ['id' => 3, 'title' => 'First', 'url' => '/a', 'classes' => ['icon'], 'target' => '_blank', 'link_title' => 'Link title'],
        ['id' => 5, 'title' => 'Second', 'url' => '/b', 'classes' => [], 'target' => '', 'link_title' => ''],
    ]],
]);

it('pins the missing-menu error without indexing an absent primary location', function (): void {
    Functions\expect('get_nav_menu_locations')->once()->andReturn(['primary' => 17]);
    $result = quicklinks_menu(null);
    expect($result)->toBeInstanceOf(WP_Error::class)->and($result->arguments)->toBe(['no_menus', 'Could not find any menus17', ['status' => 404]]);
});

it('pins the complete tray for remote, local and unassigned quick-link menus', function (string $case, string $expected): void {
    Functions\expect('site_custom_quicklinks')->once()->andReturn($case !== 'remote');
    Functions\expect('uamswp_request_quicklinks')->times($case === 'remote' ? 1 : 0)->withNoArgs()->andReturnUsing(static function (): ?bool {
        echo 'REMOTE';

        return null;
    });
    Functions\expect('get_nav_menu_locations')->times($case === 'remote' ? 0 : 1)->andReturn(match ($case) {
        'custom' => ['quick-links' => 19], 'other location' => ['primary' => 17], default => false
    });
    Functions\expect('wp_get_nav_menu_object')->times($case === 'custom' ? 1 : 0)->with(19)->andReturn((object) ['term_id' => 29]);
    Functions\expect('wp_get_nav_menu_items')->times($case === 'custom' ? 1 : 0)->with(29)->andReturn([
        (object) ['title' => 'First', 'url' => '/a', 'classes' => ['fas', 'fa-info'], 'target' => '_blank'],
        (object) ['title' => 'Second', 'url' => '/b', 'classes' => [], 'target' => ''],
    ]);
    when('esc_url')->alias(static fn ($value): string => 'SAFE:'.$value);
    expect(LibraryBrandingScenario::render('uamswp_quicklinks'))->toBe($expected);
})->with(array_map(static fn ($case, $expected): array => [$case, $expected], array_keys(json_decode(file_get_contents(__DIR__.'/../Fixtures/library-quicklinks-output.json'), true, flags: JSON_THROW_ON_ERROR)), array_values(json_decode(file_get_contents(__DIR__.'/../Fixtures/library-quicklinks-output.json'), true, flags: JSON_THROW_ON_ERROR))));

it('pins the missing-primary-location warning alongside the no-menu error', function (): void {
    Functions\expect('get_nav_menu_locations')->once()->andReturn([]);
    $result = LibraryWarnings::capture('quicklinks_menu', 'Undefined array key "primary"', static fn () => quicklinks_menu(null));
    expect($result->arguments)->toBe(['no_menus', 'Could not find any menus', ['status' => 404]]);
});
