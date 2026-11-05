<?php

declare(strict_types=1);

use Uams2020\Tests\Support\OptionString;

use function Brain\Monkey\Functions\when;

it('builds the Genesis home trail for each site and front-page context', function (array $site, bool $front, array $hrefs, array $titles): void {
    when('uams_get_site_info')->justReturn($site);
    when('is_front_page')->justReturn($front);
    when('uams_get_home_link')->justReturn('https://home.test');
    when('network_home_url')->justReturn('https://network.test/');
    when('home_url')->justReturn('https://department.test');
    when('uams_site_title')->justReturn('Department');
    when('get_blog_details')->justReturn((object) ['blogname' => 'Network']);

    $html = uams_breadcrumb_home_icon('Original');
    $document = new DOMDocument;
    $document->loadHTML('<ol>'.$html.'</ol>', LIBXML_NOERROR | LIBXML_NOWARNING);

    $xpath = new DOMXPath($document);
    $links = iterator_to_array($xpath->query('//a'));
    expect(array_map(static fn ($node) => $node->getAttribute('href'), $links))->toBe($hrefs)
        ->and(array_map(static fn ($node) => $node->getAttribute('title'), $links))->toBe($titles)
        ->and($html)->toContain('<span class="fas fa-home"></span>')
        ->and(array_map(static fn ($node) => $node->getAttribute('content'), iterator_to_array($xpath->query('//meta[@itemprop="position"]'))))->toBe(array_map(strval(...), range(1, count($hrefs))));
})->with([
    [['site' => 'uamshealth', 'subsite' => new OptionString('main'), 'department' => ''], false, ['https://home.test'], ['UAMS Health']],
    [['site' => 'uams', 'subsite' => 'medicine', 'department' => new OptionString('main')], false, ['https://home.test', 'https://department.test/'], ['University of Arkansas for Medical Sciences', 'Department']],
    [['site' => 'uams', 'subsite' => 'medicine', 'department' => new OptionString('none')], true, ['https://home.test'], ['University of Arkansas for Medical Sciences']],
    [['site' => 'uamshealth', 'subsite' => 'main', 'department' => 'main'], false, ['https://home.test'], ['UAMS Health']],
    [['site' => 'uamshealth', 'subsite' => 'clinic', 'department' => 'none'], true, ['https://home.test'], ['UAMS Health']],
    [['site' => 'inside', 'subsite' => 'main', 'department' => 'main'], true, ['https://home.test'], ['Inside UAMS']],
    [['site' => 'uams', 'subsite' => 'main', 'department' => 'dept'], true, ['https://home.test', 'https://network.test/'], ['University of Arkansas for Medical Sciences', 'Network']],
    [['site' => 'uams', 'subsite' => 'main', 'department' => 'dept'], false, ['https://home.test', 'https://network.test/', 'https://department.test/'], ['University of Arkansas for Medical Sciences', 'Network', 'Department']],
    [['site' => 'uams', 'subsite' => 'main', 'department' => 'main'], true, ['https://home.test'], ['University of Arkansas for Medical Sciences']],
    [['site' => 'uams', 'subsite' => 'main', 'department' => 'none'], false, ['https://home.test', 'https://department.test/'], ['University of Arkansas for Medical Sciences', 'Department']],
]);

it('keeps SEOPress child crumbs while setting the site and network ancestors', function (array $site, bool $front, array $expected): void {
    when('uams_get_site_info')->justReturn($site);
    when('is_front_page')->justReturn($front);
    when('uams_site_title')->justReturn('Department');
    when('uams_get_home_link')->justReturn('https://home.test');
    when('network_home_url')->justReturn('https://network.test/');
    when('get_blog_details')->justReturn((object) ['blogname' => 'Network']);

    $labels = [
        'health' => ['<span class="fas fa-home"></span><span class="sr-only">UAMS Health</span>', 'https://home.test/'],
        'inside' => ['<span class="fas fa-home"></span><span class="sr-only">Inside UAMS</span>', 'https://home.test/'],
        'uams' => ['<span class="fas fa-home"></span><span class="sr-only">University of Arkansas for Medical Sciences</span>', 'https://www.uams.edu/'],
        'uams-no-slash' => ['<span class="fas fa-home"></span><span class="sr-only">University of Arkansas for Medical Sciences</span>', 'https://www.uams.edu'],
        'network' => ['Network', 'https://network.test/'],
        'department' => ['Department', '/original/'],
        'child' => ['Child', '/child/'],
    ];
    expect(sp_pro_breadcrumbs_crumbs([['Old', '/original/'], ['Child', '/child/']]))->toBe(array_map(static fn ($label): array => $labels[$label], $expected));
})->with([
    [['site' => 'uamshealth', 'subsite' => new OptionString('main'), 'department' => ''], false, ['health', 'child']],
    [['site' => 'inside', 'subsite' => new OptionString('main'), 'department' => ''], false, ['inside', 'child']],
    [['site' => 'institute', 'subsite' => 'eye', 'department' => new OptionString('main')], false, ['uams', 'department', 'child']],
    [['site' => 'uams', 'subsite' => new OptionString('other'), 'department' => 'main'], false, ['uams', 'department', 'child']],
    [['site' => 'uams', 'subsite' => new OptionString('main'), 'department' => ''], false, ['uams', 'child']],
    [['site' => 'uams', 'subsite' => true, 'department' => 'dept'], true, ['uams', 'network', 'department', 'child']],
    [['site' => 'uams', 'subsite' => 'medicine', 'department' => new OptionString('uams')], false, ['uams-no-slash', 'department', 'child']],
    [['site' => 'uamshealth', 'subsite' => 'main', 'department' => ''], false, ['health', 'child']],
    [['site' => 'uamshealth', 'subsite' => 'clinic', 'department' => ''], false, ['health', 'department', 'child']],
    [['site' => 'inside', 'subsite' => 'main', 'department' => ''], false, ['inside', 'child']],
    [['site' => 'inside', 'subsite' => 'dept', 'department' => ''], false, ['inside', 'department', 'child']],
    [['site' => 'institute', 'subsite' => 'eye', 'department' => 'main'], false, ['uams', 'department', 'child']],
    [['site' => 'institute', 'subsite' => 'eye', 'department' => 'dept'], false, ['uams', 'network', 'department', 'child']],
    [['site' => 'uams', 'subsite' => 'other', 'department' => 'main'], false, ['uams', 'department', 'child']],
    [['site' => 'uams', 'subsite' => 'other', 'department' => 'dept'], false, ['uams', 'network', 'department', 'child']],
    [['site' => 'uams', 'subsite' => 'main', 'department' => ''], false, ['uams', 'child']],
    [['site' => 'uams', 'subsite' => 'medicine', 'department' => 'dept'], false, ['uams', 'network', 'department', 'child']],
    [['site' => 'uams', 'subsite' => 'main', 'department' => ''], true, ['uams-no-slash', 'department', 'child']],
    [['site' => 'uams', 'subsite' => 'medicine', 'department' => 'main'], false, ['uams-no-slash', 'department', 'child']],
    [['site' => 'uams', 'subsite' => 'medicine', 'department' => ''], false, ['uams-no-slash', 'department', 'child']],
    [['site' => 'uams', 'subsite' => 'medicine', 'department' => 'uams'], false, ['uams-no-slash', 'department', 'child']],
]);

it('formats the platform time string without normalizing unsupported format tokens', function (string $input, string $raw, string $expected): void {
    $timezone = date_default_timezone_get();
    date_default_timezone_set('UTC');
    $timestamp = (new DateTimeImmutable($input, new DateTimeZone('UTC')))->getTimestamp();
    Brain\Monkey\Functions\expect('strftime')->once()->with('%l:%M %P', $timestamp)->andReturn($raw);
    try {
        expect(apStyleDate($input))->toBe($expected);
    } finally {
        date_default_timezone_set($timezone);
    }
})->with([
    ['2026-01-01 09:00:00', ' 9:00 am', ' 9 a.m.'],
    ['2026-01-01 13:35:00', ' 1:35 pm', ' 1:35 p.m.'],
    ['2026-01-01 09:00:00', ' 9:00 P', ' 9 P'],
]);

// The function_exists guard permits an external site identity helper.
it('uses site identifiers supplied by a compatible external helper in breadcrumbs', function (string $site, bool $front, string $label, string $url, array $tail): void {
    when('uams_get_site_info')->justReturn(['site' => new OptionString($site), 'subsite' => 'main', 'department' => 'main']);
    when('is_front_page')->justReturn($front);
    when('uams_get_home_link')->justReturn('https://home.test');
    when('uams_site_title')->justReturn('Department');
    when('home_url')->justReturn('https://department.test');

    $crumbs = sp_pro_breadcrumbs_crumbs([['Old', '/original/'], ['Child', '/child/']]);
    expect($crumbs)->toBe([
        ['<span class="fas fa-home"></span><span class="sr-only">'.$label.'</span>', $url],
        ...$tail,
    ]);
    $document = new DOMDocument;
    $document->loadHTML('<ol>'.uams_breadcrumb_home_icon('Original').'</ol>', LIBXML_NOERROR | LIBXML_NOWARNING);

    $xpath = new DOMXPath($document);
    expect($xpath->query('//a')->item(0)->getAttribute('title'))->toBe($label);
})->with([
    ['uamshealth', false, 'UAMS Health', 'https://home.test/', [['Child', '/child/']]],
    ['uamshealth', true, 'UAMS Health', 'https://home.test/', [['Child', '/child/']]],
    ['inside', true, 'Inside UAMS', 'https://home.test/', [['Child', '/child/']]],
    ['institute', false, 'University of Arkansas for Medical Sciences', 'https://www.uams.edu/', [['Department', '/original/'], ['Child', '/child/']]],
]);
