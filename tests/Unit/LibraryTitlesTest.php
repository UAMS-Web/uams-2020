<?php

declare(strict_types=1);

use Brain\Monkey\Functions;

use function Brain\Monkey\Functions\when;

function libraryTitleContext(array $site, array $flags = [], string $name = 'Local   Name', string $root = 'Root   Name'): void
{
    when('uams_get_site_info')->justReturn($site);
    when('get_the_title')->justReturn('Page Title');
    when('get_the_id')->justReturn(41);
    foreach (['is_search', 'is_archive', 'is_category', 'is_tag', 'is_tax', 'is_home', 'is_front_page', 'is_404'] as $function) {
        when($function)->justReturn($flags[$function] ?? false);
    }

    Functions\expect('get_bloginfo')->once()->with('name')->andReturn($name);
    Functions\expect('get_blog_details')->once()->with(1)->andReturn((object) ['blogname' => $root]);
    Functions\expect('get_post_meta')->once()->with(41, '_seopress_titles_title', true)->andReturn($flags['seo'] ?? '');
}

$libraryTitleCases = [
    ['uamshealth', 'main', 'main', "UAMS Health | Arkansas's Leading Academic Medical Center", 'UAMS Health'],
    ['uamshealth', 'dept', 'main', 'Local Name | UAMS Health', 'UAMS Local Name'],
    ['uamshealth', 'other', 'main', 'Local Name | UAMS Health', 'UAMS Health'],
    ['inside', 'main', 'main', 'Inside UAMS', 'Inside UAMS'],
    ['inside', 'dept', 'main', 'Local Name | Inside UAMS', 'UAMS Local Name'],
    ['inside', 'none', 'main', 'Local Name | Inside UAMS', 'Inside UAMS'],
    ['institute', 'any', 'main', 'UAMS Local Name', 'UAMS Local Name'],
    ['institute', 'any', 'dept', 'Local Name | UAMS Root Name', 'UAMS Local Name'],
    ['institute', 'any', 'other', 'Local Name | UAMS Root Name', 'UAMS Root Name'],
    ['uams', 'medicine', 'main', 'UAMS Local Name', 'UAMS Local Name'],
    ['uams', 'medicine', '', 'UAMS Local Name', 'UAMS Local Name'],
    ['uams', 'medicine', 'dept', 'Local Name | UAMS Root Name', 'UAMS Local Name'],
    ['uams', 'medicine', 'none', 'Local Name | UAMS Root Name', 'UAMS Root Name'],
    ['uams', 'main', 'dept', 'University of Arkansas for Medical Sciences (UAMS)', 'UAMS'],
    ['uams', 'none', 'dept', 'Local Name | UAMS Root Name', 'Local Name'],
    ['uams', 'none', 'none', 'Local Name | UAMS Root Name', 'UAMS Root Name'],
    ['uams', 'none', 'main', 'Local Name', 'Local Name'],
    ['uams', 'none', '', 'Local Name', 'Local Name'],
    ['uams', 'none', 'uams', 'Local Name', 'Local Name'],
    ['unknown', 'other', 'main', 'UAMS Local Name', 'UAMS Local Name'],
];
$libraryTitleMatrix = [];
foreach ($libraryTitleCases as [$site, $subsite, $department, $home, $suffix]) {
    foreach (['normal', 'is_home', 'is_front_page', 'is_404'] as $mode) {
        $libraryTitleMatrix[$site.'/'.$subsite.'/'.$department.'/'.$mode] = [
            ['site' => $site, 'subsite' => $subsite, 'department' => $department], $mode,
            in_array($mode, ['is_home', 'is_front_page'], true) ? $home : ($mode === 'is_404' ? 'Page Not Found' : 'Page Title').' | '.$suffix,
        ];
    }
}

it('pins branding titles for each site, department and page state', function (array $site, string $mode, string $expected): void {
    libraryTitleContext($site, [$mode => true]);
    expect(uamswp_title('ignored'))->toBe($expected);
})->with($libraryTitleMatrix);

it('pins page title precedence across search, archives, taxonomies and SEO overrides', function (array $flags, $typeObject, string $expected): void {
    libraryTitleContext(['site' => 'uams', 'subsite' => 'main', 'department' => 'main'], $flags);
    if ($flags['is_archive'] ?? false) {
        Functions\expect('get_post_type')->once()->with(41)->andReturn('event');
        Functions\expect('get_post_type_object')->once()->with('event')->andReturn($typeObject);
    }

    $term = ($flags['is_archive'] ?? false) && (($flags['is_category'] ?? false) || ($flags['is_tag'] ?? false) || ($flags['is_tax'] ?? false));
    Functions\expect('single_term_title')->times($term ? 1 : 0)->with('', false)->andReturn('Term Title');
    expect(uamswp_title('ignored'))->toBe($expected.' | UAMS');
})->with([
    [['is_search' => true], null, 'Search Results'],
    [['is_search' => true, 'is_archive' => true], (object) ['label' => 'Events'], 'Events'],
    [['is_archive' => true], null, ''],
    [['is_archive' => true, 'is_category' => true], (object) ['label' => 'Events'], 'Term Title'],
    [['is_archive' => true, 'is_tag' => true], (object) ['label' => 'Events'], 'Term Title'],
    [['is_archive' => true, 'is_tax' => true], (object) ['label' => 'Events'], 'Term Title'],
    [['is_archive' => true, 'is_tax' => true, 'seo' => 'SEO Title'], (object) ['label' => 'Events'], 'SEO Title'],
    [['is_category' => true], null, 'Page Title'],
    [['seo' => '0'], null, 'Page Title'], [['seo' => 'SEO Title'], null, 'SEO Title'],
]);

it('pins the current substring direction for institutional prefixes', function (string $name, string $root, string $expected): void {
    libraryTitleContext(['site' => 'institute', 'subsite' => 'eye', 'department' => 'dept'], ['is_home' => true], $name, $root);
    expect(uamswp_title('ignored'))->toBe($expected);
})->with([
    ['UAMS', 'UAMS', 'UAMS | UAMS'],
    ['University of Arkansas for Medical Sciences', 'University of Arkansas for Medical Sciences', 'University of Arkansas for Medical Sciences | University of Arkansas for Medical Sciences'],
    ['', '', ' | '],
    ['UAMS Local', 'UAMS Root', 'UAMS Local | UAMS UAMS Root'],
    ['AMS', 'Medical', 'AMS | Medical'],
]);
