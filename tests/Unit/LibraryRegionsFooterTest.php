<?php

declare(strict_types=1);

use Brain\Monkey\Functions;

use function Brain\Monkey\Functions\when;
use function Patchwork\relay;

it('returns null for both region helpers when GeoIP is unavailable', function (): void {
    when('function_exists')->alias(static fn ($name) => $name === 'geoip_detect2_get_info_from_current_ip' ? false : relay());
    expect(is_in_region(['central']))->toBeNull()->and(is_not_in_region(['central']))->toBeNull();
});

it('pins every region city against its configured group', function (string $region, string $city): void {
    Functions\expect('geoip_detect2_get_info_from_current_ip')->twice()->withNoArgs()->andReturn((object) ['city' => (object) ['name' => $city], 'subdivisions' => [(object) ['name' => 'Arkansas']]]);
    expect(is_in_region([$region]))->toBeTrue()->and(is_not_in_region([$region]))->toBeFalse();
})->with(json_decode(file_get_contents(__DIR__.'/../Fixtures/library-region-cities.json'), true, flags: JSON_THROW_ON_ERROR));

it('pins state, missing-city, region order and unknown-region boundaries', function ($city, string $state, array $regions, ?bool $included, ?bool $excluded): void {
    Functions\expect('geoip_detect2_get_info_from_current_ip')->twice()->andReturn((object) ['city' => (object) ['name' => $city], 'subdivisions' => [(object) ['name' => $state]]]);
    expect(is_in_region($regions))->toBe($included)->and(is_not_in_region($regions))->toBe($excluded);
})->with([
    ['Little Rock', 'Texas', ['central'], false, true],
    ['Little Rock', 'arkansas', ['central'], false, true],
    ['Unmapped', 'Arkansas', ['central'], null, true],
    ['Little Rock', 'Arkansas', [], null, true],
    ['Little Rock', 'Arkansas', ['unknown', 'central'], false, false],
    ['Little Rock', 'Arkansas', ['central', 'unknown'], true, false],
    ['Little Rock', 'Arkansas', ['northwest', 'central'], true, false],
    ['Little Rock', 'Arkansas', ['northeast', 'southwest', 'southeast'], null, true],
    ['little rock', 'Arkansas', ['central'], null, true],
    ['Unmapped', 'Arkansas', ['unknown'], false, true],
]);

it('ignores absent or nonnumeric footer-widget support', function ($support): void {
    Functions\expect('get_theme_support')->once()->with('genesis-footer-widgets')->andReturn($support);
    Functions\expect('is_active_sidebar')->never();
    expect(uamswp_footer_widget_areas('old', 999))->toBeNull();
})->with([[false], [[]], [[null]], [['not numeric']]]);

it('does not render footer widgets when the first area is inactive', function (): void {
    Functions\expect('get_theme_support')->once()->with('genesis-footer-widgets')->andReturn([3]);
    Functions\expect('is_active_sidebar')->once()->with('footer-1')->andReturn(false);
    Functions\expect('dynamic_sidebar')->never();
    expect(uamswp_footer_widget_areas('old', 999))->toBeNull();
});

it('pins footer columns, integer coercion, boundaries and ordered sidebar output', function ($count, int $rendered, string $class): void {
    Functions\expect('get_theme_support')->once()->with('genesis-footer-widgets')->andReturn([$count]);
    Functions\expect('is_active_sidebar')->once()->with('footer-1')->andReturn(true);
    $seen = [];
    when('dynamic_sidebar')->alias(function ($id) use (&$seen, $rendered): void {
        expect(count($seen))->toBeLessThan($rendered);
        expect($id)->toBe('footer-'.(count($seen) + 1));
        $seen[] = $id;
        echo '['.$id.']';
    });
    if ($rendered > 0) {
        Functions\expect('genesis_markup')->once()->with(['html5' => '<div %s>', 'xhtml' => '<div id="footer-widgets" class="footer-widgets">', 'context' => 'footer-widgets'])->andReturn('<OUTER>');
        Functions\expect('genesis_structural_wrap')->once()->with('footer-widgets', 'open', 0)->andReturn('<WRAP>');
        Functions\expect('genesis_structural_wrap')->once()->with('footer-widgets', 'close', 0)->andReturn('</WRAP>');
    } else {
        Functions\expect('genesis_markup')->never();
        Functions\expect('genesis_structural_wrap')->never();
    }

    $inside = '';
    $ids = [];
    for ($i = 1; $i <= $rendered; $i++) {
        $ids[] = 'footer-'.$i;
        $inside .= '<div class="footer-widgets-'.$i.' '.$class.' widget-area">[footer-'.$i.']</div>';
    }

    expect(uamswp_footer_widget_areas('old', 999))->toBe($rendered > 0 ? '<OUTER><WRAP>'.$inside.'</WRAP></div>' : '')->and($seen)->toBe($ids);
})->with([
    [0, 0, 'col-sm-12'], [-1, 0, 'col-sm-12'], [1, 1, 'col-sm-12'], ['2', 2, 'col-sm-6'],
    [3, 3, 'col-sm-4'], [4, 4, 'col-sm-3'], [6, 6, 'col-sm-2'], [12, 12, 'col-sm-1'],
    [5, 5, 'col-sm-12'], ['2.9', 2, 'col-sm-6'],
]);
