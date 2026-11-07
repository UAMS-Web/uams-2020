<?php

declare(strict_types=1);

use PHPUnit\Framework\Assert;
use Uams2020\Tests\Support\AcfFieldSchemaLoader;

use function Brain\Monkey\Functions\when;
use function Patchwork\relay;

$acfFieldCases = json_decode(file_get_contents(__DIR__.'/../Fixtures/acf-field-schemas.json'), true, flags: JSON_THROW_ON_ERROR);

it('pins complete field definitions for block, module and page-option contexts', function (array $case): void {
    when('class_exists')->alias(static fn ($name) => $name === 'UAMS_Syndicate_News_Base' ? $case['news'] : relay());
    $actual = AcfFieldSchemaLoader::load($case['name'], $case['suffix'], $case['children']);
    Assert::assertTrue($case['expected'] === $actual, 'Field schema changed: '.$case['name'].' '.$case['suffix'].' news='.(int) $case['news']);
})->with(array_map(static fn (array $case): array => [$case], $acfFieldCases));

it('composes the real module fields into the established complete registration schema', function (bool $newsAvailable): void {
    when('class_exists')->alias(static fn ($name) => $name === 'UAMS_Syndicate_News_Base' ? $newsAvailable : relay());
    $mapping = [
        'content' => 'content', 'action_bar' => 'action-bar', 'call_out' => 'call-out', 'cta' => 'cta',
        'hero' => 'hero', 'link_list' => 'link-list', 'news' => 'news', 'overlay' => 'overlay',
        'post_tile' => 'post-category-tile', 'post_tiles' => 'post-category-tiles',
        'side_by_side' => 'image-side-by-side', 'stacked' => 'stacked', 'livewhale' => 'livewhale', 'gallery' => 'gallery',
    ];
    $children = [];
    foreach ($mapping as $variable => $name) {
        $children[$variable] = AcfFieldSchemaLoader::load($name, '_m');
    }

    $actual = AcfFieldSchemaLoader::load('modules', '_m', $children);
    $fixture = json_decode(file_get_contents(__DIR__.'/../Fixtures/page-builder-registrations'.($newsAvailable ? '' : '-without-plugins').'.json'), true, flags: JSON_THROW_ON_ERROR);
    $groups = array_values(array_filter($fixture['groups'], static fn (array $group): bool => $group['key'] === 'group_5d1263f48f6fb'));
    expect($groups)->toHaveCount(1);
    Assert::assertTrue($groups[0]['fields'] === $actual, 'Composed module schema changed for news='.(int) $newsAvailable);
})->with([true, false]);
