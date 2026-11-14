<?php

declare(strict_types=1);

use PHPUnit\Framework\Assert;
use Uams2020\Tests\Support\BlockTemplateScenario;
use Uams2020\Tests\Support\TraceString;

use function Brain\Monkey\Functions\when;
use function Patchwork\relay;

$blockTemplateCases = json_decode(file_get_contents(__DIR__.'/../Fixtures/block-templates.json'), true, flags: JSON_THROW_ON_ERROR);
$blockCasesByName = [];
foreach ($blockTemplateCases as $case) {
    $blockCasesByName[$case['name']][] = $case;
}

foreach ($blockCasesByName as $name => $cases) {
    /**
     * Gallery has fourteen explicit density literals whose 1-to-0 mutations are equivalent:
     * the real first-party dimension helper scales only densities greater than 1.
     * Native sizing is executed here; no mock receipt distinguishes the equal inputs.
     * Fourteen first class-name appends equal assignment to an empty local string.
     * Unused module-ID numeric literals and concatenations preserve arithmetic and
     * conversion effects; changing plus to minus stays tested because errors differ.
     * Side-image scalar fallbacks become empty arrays immediately. Its unused width
     * index/default changes preserve the real image lookup and WordPress filters.
     * The final gallery crop predicate changes only an unused ratio assignment;
     * native comparison effects are checked separately below.
     * The double-category local index is never read. The strip-tags flags 49, 50
     * and 51 are all true to WordPress, which has no raw-flag filter.
     * Stacked initial more-link strings are overwritten before use or remain unused.
     */
    it('pins '.$name.' block markup and external boundary arguments', function (array $case): void {
        $actual = BlockTemplateScenario::render($case);
        Assert::assertTrue($case['expected'] === $actual, 'Block output or calls changed: '.$case['name'].' / '.$case['label']);
    })->with(array_combine(array_map(static fn (array $case): string => $case['name'].' / '.$case['label'], $cases), array_map(static fn (array $case): array => [$case], $cases)));
}

$blockBaseCases = array_column(array_filter($blockTemplateCases, static fn (array $case): bool => $case['label'] === 'fields'), null, 'name');

it('preserves livewhale module caller string conversions', function (string $source) use ($blockBaseCases): void {
    $case = $blockBaseCases['livewhale'];
    $module = ['acf_fc_layout' => 'modules_uams_livewhale', 'livewhale_heading' => 'Calendar', 'livewhale_id' => 23, 'livewhale_background_color' => 'bg-white', 'livewhale_geo' => 'include', 'livewhale_geo_region' => []];
    $index = $source === 'index' ? new TraceString('7', '[index-conversion]') : 0;
    if ($source === 'anchor') {
        $module['anchor_id'] = new TraceString('module-anchor', '[anchor-conversion]');
    }

    when('get_stylesheet_directory')->justReturn(dirname(__DIR__, 2));
    $actual = BlockTemplateScenario::render($case, static fn () => uamswp_module($module, $index));
    Assert::assertStringContainsString(($source === 'index' ? '[index-conversion]' : '[anchor-conversion]').'<section', $actual['html']);
    Assert::assertStringContainsString('id="23"', $actual['html']);
})->with(['index', 'anchor']);

it('preserves native gallery crop comparison conversions', function (string $crop, int $comparisons) use ($blockBaseCases): void {
    $case = $blockBaseCases['gallery'];
    $case['fields']['gallery_crop'] = new TraceString($crop, '[crop-conversion]');
    $case['fields']['gallery_images'] = [];
    $actual = BlockTemplateScenario::render($case);
    Assert::assertStringStartsWith(str_repeat('[crop-conversion]', $comparisons), $actual['html']);
    Assert::assertSame($comparisons, substr_count($actual['html'], '[crop-conversion]'));
})->with([['1.7778', 2], ['1.3333', 3], ['1', 4], ['0.75', 4]]);

it('retains native gallery failures for malformed crop ratios', function () use ($blockBaseCases): void {
    $case = $blockBaseCases['gallery'];
    $case['fields']['gallery_crop'] = 'portrait';
    $this->expectException(TypeError::class);
    BlockTemplateScenario::render($case);
});

foreach ($blockBaseCases as $name => $baseCase) {
    $geoKeys = array_values(array_filter(array_keys($baseCase['fields']), static fn (string $key): bool => str_ends_with($key, '_geo')));
    if ($geoKeys === []) {
        continue;
    }

    if (count($geoKeys) !== 1) {
        throw new LogicException('Ambiguous geo field for '.$name);
    }

    $geoKey = $geoKeys[0];
    it('preserves '.$name.' loose geo comparisons for caller values', function (string $selection, int $conversions) use ($baseCase, $geoKey): void {
        $case = $baseCase;
        $case['fields'][$geoKey] = new TraceString($selection, '[geo-comparison]');
        $case['fields'][$geoKey.'_region'] = ['AR'];
        $case['variables']['is_preview'] = false;
        $case['admin'] = false;
        $case['in_region'] = true;
        $case['out_region'] = true;
        $actual = BlockTemplateScenario::render($case);
        $expected = str_repeat('[geo-comparison]', $conversions).$baseCase['expected']['html'];
        Assert::assertTrue($expected === $actual['html'], 'Geo comparison or visibility changed for '.$case['name'].' '.$selection);
    })->with([['include', 1], ['exclude', 2]]);
}

$blockCasesByLabel = [];
foreach ($blockTemplateCases as $case) {
    $blockCasesByLabel[$case['name']][$case['label']] = $case;
}

$assertConvertedMarkup = static function (string $html, string $expected, string $marker, int $conversions): void {
    Assert::assertSame($conversions, substr_count($html, $marker));
    Assert::assertTrue($expected === str_replace($marker, '', $html), 'Caller conversion changed the established block markup.');
};

it('preserves native news format comparisons', function (string $format, int $conversions) use ($blockCasesByLabel, $assertConvertedMarkup): void {
    $case = $blockCasesByLabel['news']['format-'.$format];
    $case['fields']['news_format'] = new TraceString($format, '[format-conversion]');
    $actual = BlockTemplateScenario::render($case);
    $assertConvertedMarkup($actual['html'], $case['expected']['html'], '[format-conversion]', $conversions);
})->with([['grid', 2], ['cards', 3], ['full', 4], ['side', 5]]);

it('preserves native CTA action comparisons', function (string $label, string $action) use ($blockCasesByLabel, $assertConvertedMarkup): void {
    $case = $blockCasesByLabel['cta'][$label];
    $case['fields']['cta_bar_action_type'] = new TraceString($action, '[action-conversion]');
    $actual = BlockTemplateScenario::render($case);
    $assertConvertedMarkup($actual['html'], $case['expected']['html'], '[action-conversion]', 5);
})->with([['fields', 'url'], ['action-phone-large', 'phone'], ['action-none-large', 'none']]);

it('preserves native CTA size comparisons', function (string $label, string $size, int $conversions) use ($blockCasesByLabel, $assertConvertedMarkup): void {
    $case = $blockCasesByLabel['cta'][$label];
    $case['fields']['cta_bar_size'] = new TraceString($size, '[size-conversion]');
    $actual = BlockTemplateScenario::render($case);
    $assertConvertedMarkup($actual['html'], $case['expected']['html'], '[size-conversion]', $conversions);
})->with([['fields', 'large', 3], ['action-phone-small', 'small', 2]]);

it('preserves native side-by-side layout comparisons', function (string $layout, int $conversions) use ($blockCasesByLabel, $assertConvertedMarkup): void {
    $case = $blockCasesByLabel['image-side-by-side']['layout-'.$layout];
    $case['fields']['side_text_layout'] = new TraceString($layout, '[layout-conversion]');
    $actual = BlockTemplateScenario::render($case);
    $assertConvertedMarkup($actual['html'], $case['expected']['html'], '[layout-conversion]', $conversions);
})->with([['body-only', 5], ['link-list', 5], ['blockquote-citation', 5], ['blockquote-citation-link', 6]]);

it('preserves native gallery column comparisons', function (string $columns, int $conversions) use ($blockCasesByLabel, $assertConvertedMarkup): void {
    $case = $blockCasesByLabel['gallery']['gallery-'.$columns.'-none'];
    $case['fields']['gallery_columns'] = new TraceString($columns, '[column-conversion]');
    $actual = BlockTemplateScenario::render($case);
    $assertConvertedMarkup($actual['html'], $case['expected']['html'], '[column-conversion]', $conversions);
})->with([['2', 1], ['3', 2], ['4', 3]]);

it('preserves native gallery crop-item comparisons', function () use ($blockCasesByLabel, $assertConvertedMarkup): void {
    $case = $blockCasesByLabel['gallery']['gallery-3-none'];
    $case['fields']['gallery_crop'] = [new TraceString('none', '[crop-item-conversion]')];
    $actual = BlockTemplateScenario::render($case);
    $assertConvertedMarkup($actual['html'], $case['expected']['html'], '[crop-item-conversion]', 1);
});

it('preserves native hero automatic-color comparisons', function () use ($blockBaseCases, $assertConvertedMarkup): void {
    $case = $blockBaseCases['hero'];
    $case['fields']['hero'][0]['hero_background_color'] = new TraceString('auto', '[color-conversion]');
    $actual = BlockTemplateScenario::render($case);
    $assertConvertedMarkup($actual['html'], $case['expected']['html'], '[color-conversion]', 1);
});

it('inherits counter starts from a true boolean field', function () use ($blockBaseCases): void {
    $case = $blockBaseCases['counter'];
    foreach ($case['fields']['counter_list_section'] as &$row) {
        $row['counter_list_section_start_inherit'] = true;
    }

    unset($row);
    $actual = BlockTemplateScenario::render($case);
    Assert::assertTrue($case['expected']['html'] === $actual['html'], 'Boolean counter inheritance changed.');
});

it('preserves native inherited custom-start comparisons', function () use ($blockCasesByLabel, $assertConvertedMarkup): void {
    $case = $blockCasesByLabel['counter']['start-custom'];
    $case['fields']['counter_list_start'] = new TraceString('custom', '[start-conversion]');
    $actual = BlockTemplateScenario::render($case);
    $assertConvertedMarkup($actual['html'], $case['expected']['html'], '[start-conversion]', 4);
});

it('deduplicates section classes and removes empty or zero tokens', function () use ($blockBaseCases): void {
    $case = $blockBaseCases['section'];
    $case['variables']['block']['className'] = 'uams-module  custom alignfull custom class 0 ';
    $actual = BlockTemplateScenario::render($case);
    Assert::assertTrue($case['expected']['html'] === $actual['html'], 'Section class normalization changed.');
});

it('uses the legacy link-list fallback icon when icons are enabled', function () use ($blockCasesByLabel): void {
    $case = $blockCasesByLabel['link-list-2']['toggle-link_list_icons'];
    $case['fields']['link_list_section'][1]['link_list_section_icon'] = false;
    $actual = BlockTemplateScenario::render($case);
    Assert::assertSame(1, substr_count($actual['html'], 'class="fas fa-link fa-stack-1x fa-inverse"'));
    Assert::assertStringContainsString($case['fields']['link_list_section'][0]['link_list_section_icon'].' fa-stack-1x fa-inverse', $actual['html']);
});

it('floors fractional counter values above the display threshold', function () use ($blockBaseCases): void {
    $case = $blockBaseCases['counter'];
    foreach ($case['fields']['counter_list_section'] as &$row) {
        $row['counter_list_section_start_inherit'] = false;
        $row['counter_list_section_start_override'] = 'custom';
        $row['counter_list_section_start_custom'] = '2024-03-15T11:59:57+00:00';
        $row['counter_list_section_rate'] = 0.5;
    }

    unset($row);
    $actual = BlockTemplateScenario::render($case);
    Assert::assertSame(2, substr_count($actual['html'], '<span class="value">1</span>'));
});

it('hides a phone CTA whose phone number is empty', function () use ($blockCasesByLabel): void {
    $case = $blockCasesByLabel['cta']['action-none-large'];
    $case['fields']['cta_bar_action_type'] = 'phone';
    $case['fields']['cta_bar_phone'] = false;
    $actual = BlockTemplateScenario::render($case);
    Assert::assertTrue($case['expected']['html'] === $actual['html'], 'Missing phone did not retain the established no-action markup.');
});

it('chooses more-link contrast for each supported light and dark background', function (string $name, string $background, string $color) use ($blockCasesByLabel): void {
    $case = $blockCasesByLabel[$name][$name === 'gallery' ? 'gallery-3-none' : 'more'];
    $case['fields'][$name.'_background_color'] = $background;
    $actual = BlockTemplateScenario::render($case);
    Assert::assertStringContainsString('class="btn btn-'.($name === 'stacked' ? 'outline-' : '').$color.'"', $actual['html']);
})->with([
    ['gallery', 'bg-gray', 'primary'], ['gallery', 'bg-auto', 'primary'], ['gallery', 'bg-red', 'white'],
    ['stacked', 'bg-gray', 'primary'], ['stacked', 'bg-auto', 'primary'], ['stacked', 'bg-red', 'white'],
]);

it('prints the escaped target on the more link only when the link has one', function (string $name, string $target, bool $printed) use ($blockCasesByLabel): void {
    $case = $blockCasesByLabel[$name][$name === 'gallery' ? 'gallery-3-none' : 'more'];
    $case['fields'][$name.'_more_button_url']['target'] = $target;
    $actual = BlockTemplateScenario::render($case);
    Assert::assertSame(1, preg_match('/<a [^\n]*?class="btn btn-[a-z-]+"[^\n]*?>'.$name.'_more_button_text/', $actual['html'], $matches), 'The more link was not rendered.');
    Assert::assertSame($printed, str_contains($matches[0], ' target="ATTR('.$target.')"'));
    Assert::assertSame($printed, str_contains($matches[0], ' target='));
    Assert::assertStringEndsWith(($printed ? ' target="ATTR('.$target.')"' : '"').'>'.$name.'_more_button_text', $matches[0]);
})->with([
    ['gallery', '_blank', true],
    ['gallery', '', false],
    ['stacked', '_blank', true],
    ['stacked', '', false],
]);

it('keeps a blank more-link URL when the stored stacked link is false', function () use ($blockCasesByLabel): void {
    $case = $blockCasesByLabel['stacked']['more'];
    $case['fields']['stacked_more_button_url'] = false;
    $actual = BlockTemplateScenario::render($case);
    Assert::assertStringContainsString('href="" class="btn btn-outline-primary"', $actual['html']);
    Assert::assertSame(1, preg_match('/<a [^\n]*?class="btn btn-outline-[a-z]+"[^\n]*?>stacked_more_button_text/', $actual['html'], $matches), 'The more link was not rendered.');
    Assert::assertStringNotContainsString(' target=', $matches[0]);
    Assert::assertStringEndsWith('">stacked_more_button_text', $matches[0]);
});

it('keeps empty nested link targets in the printed false arm', function (string $name, string $repeater, string $urlKey) use ($blockBaseCases): void {
    $case = $blockBaseCases[$name];
    foreach ($case['fields'][$repeater] as &$row) {
        $row[$urlKey]['target'] = '';
    }

    unset($row);
    $actual = BlockTemplateScenario::render($case);
    $attribute = $name === 'link-list-2' ? ' target="_blank"' : ' target="ATTR(_blank)"';
    $expected = str_replace($attribute, '', $case['expected']['html']);
    Assert::assertTrue($expected === $actual['html'], 'Empty nested target changed printed block markup.');
})->with([
    ['hero', 'hero', 'hero_button_url'],
    ['link-list-2', 'link_list_section', 'link_list_section_url'],
    ['stacked', 'stacked_section', 'stacked_section_button_url'],
]);

it('does not carry the previous row link into a row without one', function (string $name, string $repeater, string $urlKey) use ($blockBaseCases): void {
    $case = $blockBaseCases[$name];
    $first = $case['fields'][$repeater][0];
    $second = $first;
    $second[$urlKey] = false;
    $case['fields'][$repeater] = [$first, $second];
    $actual = BlockTemplateScenario::render($case);
    Assert::assertSame(2, preg_match_all('/<a [^>]*stretched-link[^>]*>/', $actual['html'], $matches), 'Expected one anchor per row.');
    Assert::assertStringContainsString(' target=', $matches[0][0]);
    Assert::assertStringNotContainsString(' target=', $matches[0][1]);
    Assert::assertSame(1, preg_match('/href="(URL\(\))?"/', $matches[0][1]), 'The second row reused the first row link.');
})->with([
    ['link-list-2', 'link_list_section', 'link_list_section_url'],
    ['stacked', 'stacked_section', 'stacked_section_button_url'],
]);

it('renders an action bar with no rows without reading an undefined row count', function (mixed $rows) use ($blockBaseCases): void {
    $case = $blockBaseCases['action-bar'];
    $case['fields']['action_bar_section'] = $rows;
    $warnings = [];
    set_error_handler(static function (int $level, string $message) use (&$warnings): bool {
        $warnings[] = $message;

        return true;
    });
    try {
        $actual = BlockTemplateScenario::render($case);
    } finally {
        restore_error_handler();
    }

    Assert::assertSame([], $warnings);
    Assert::assertStringContainsString(' count-3 ', $actual['html']);
})->with([[[]], [false]]);

it('keeps an empty CTA link target without adding text to the anchor', function () use ($blockBaseCases): void {
    $case = $blockBaseCases['cta'];
    $case['fields']['cta_bar_button_url']['target'] = '';
    $actual = BlockTemplateScenario::render($case);
    $expected = str_replace(' target="ATTR(_blank)"', '', $case['expected']['html']);
    Assert::assertTrue($expected === $actual['html'], 'Empty CTA target changed the anchor.');
});

it('keeps a blank hero URL from a nonempty link group', function () use ($blockBaseCases): void {
    $case = $blockBaseCases['hero'];
    $case['fields']['hero'][0]['hero_button_url']['url'] = '';
    $actual = BlockTemplateScenario::render($case);
    Assert::assertSame(1, substr_count($actual['html'], 'href="URL()"'));
});

it('keeps an empty single-category image alternative in either image path', function (bool $resizer) use ($blockBaseCases): void {
    $case = $blockBaseCases['post-category-tile'];
    $case['resizer'] = $resizer;
    $case['meta_alt'] = '';
    $actual = BlockTemplateScenario::render($case);
    Assert::assertStringContainsString('alt="ATTR()"', $actual['html']);
})->with([true, false]);

it('omits an absent quote speaker title while retaining the quotation', function () use ($blockCasesByLabel): void {
    $case = $blockCasesByLabel['image-side-by-side']['layout-blockquote-citation'];
    $case['fields']['side_quote_speaker_title'] = false;
    $actual = BlockTemplateScenario::render($case);
    Assert::assertStringNotContainsString('<br>', $actual['html']);
    Assert::assertStringContainsString('HTML('.$case['fields']['side_quote_speaker_name'].')', $actual['html']);
});

it('preserves partial CTA link groups and their observable escaping calls', function (array $group, array $prefix, bool $hasLink) use ($blockBaseCases): void {
    $case = $blockBaseCases['image-side-by-side'];
    $escapes = array_values(array_filter($case['expected']['calls'], static fn (array $call): bool => $call[0] === 'esc_attr'));
    Assert::assertSame([['esc_attr', '_blank'], ['esc_attr', 'CTA description'], ['esc_attr', $case['fields']['side_heading']]], array_slice($escapes, 0, 3));
    $case['fields']['side_cta'] = $group;
    $actual = BlockTemplateScenario::render($case);
    $expected = array_merge(array_map(static fn (string $value): array => ['esc_attr', $value], $prefix), array_slice($escapes, 3));
    $observed = array_values(array_filter($actual['calls'], static fn (array $call): bool => $call[0] === 'esc_attr'));
    Assert::assertTrue($expected === $observed, 'Partial CTA changed the actual attribute-escaping boundary.');
    Assert::assertSame($hasLink ? 1 : 0, substr_count($actual['html'], '<a class="btn btn-primary"'));
    Assert::assertStringContainsString('HTML(Partial CTA)', $actual['html']);
    if (! $hasLink) {
        $anchor = '<a class="btn btn-primary" href="URL(https://example.test/go?x="<&)" aria-label="ATTR(CTA description)" target="ATTR(_blank)" data-moduletitle="ATTR('.$case['fields']['side_heading'].')">';
        $expectedHtml = str_replace($anchor.'HTML(CTA <&)', 'HTML(Partial CTA)', $case['expected']['html'], $replaced);
        Assert::assertSame(1, $replaced);
        Assert::assertTrue($expectedHtml === $actual['html'], 'A partial CTA changed the saved complete markup.');
    }

    if ($hasLink) {
        Assert::assertStringContainsString('<a class="btn btn-primary" href="URL(https://example.test/partial)" data-moduletitle="ATTR('.$case['fields']['side_heading'].')">', $actual['html']);
    }
})->with([
    [['side_cta_text' => 'Partial CTA'], [], false],
    [['side_cta_text' => 'Partial CTA', 'side_cta_url' => ['url' => 'https://example.test/partial', 'target' => '']], ['side_heading <&"'], true],
    [['side_cta_text' => 'Partial CTA', 'side_cta_url' => ['url' => '', 'target' => '_blank']], ['_blank'], false],
]);

it('preserves explicitly supplied false and true news link flags', function (bool $link) use ($blockCasesByLabel): void {
    $case = $blockCasesByLabel['news'][$link ? 'fields' : 'toggle-news_include_link'];
    $expected = $blockCasesByLabel['news'][$link ? 'toggle-news_include_link' : 'fields']['expected']['html'];
    $case['variables']['link'] = $link;
    $actual = BlockTemplateScenario::render($case);
    Assert::assertTrue($expected === $actual['html'], 'A supplied news link flag was overwritten.');
    Assert::assertNotContains(['get_field', 'news_include_link', []], $actual['calls']);
})->with([false, true]);

it('removes each shortcode delimiter from news text fields', function () use ($blockBaseCases): void {
    $case = $blockBaseCases['news'];
    $case['fields']['news_category'] = 'category[one]"tail';
    $case['fields']['news_advanced_cat'] = 'advanced[two]"tail';
    $case['fields']['news_title'] = 'Title[three]"tail';
    $actual = BlockTemplateScenario::render($case);
    foreach (['category="ATTR(TEXT(categoryonetail))"', 'advanced_cat="ATTR(TEXT(advancedtwotail))"', 'news_title="ATTR(TEXT(Titlethreetail))"'] as $attribute) {
        Assert::assertStringContainsString($attribute, $actual['html']);
    }
});

it('preserves news module caller index and anchor conversions', function (string $source) use ($blockBaseCases): void {
    $case = $blockBaseCases['news'];
    $module = ['acf_fc_layout' => 'modules_news_grid'] + $case['fields'];
    $index = $source === 'index' ? new TraceString('7', '[news-index-conversion]') : 0;
    if ($source === 'anchor') {
        $module['anchor_id'] = new TraceString('module-anchor', '[news-anchor-conversion]');
    }

    when('class_exists')->alias(static fn ($name) => $name === 'UAMS_Syndicate_News_Base' ? true : relay());
    when('get_stylesheet_directory')->justReturn(dirname(__DIR__, 2));

    $actual = BlockTemplateScenario::render($case, static fn () => uamswp_module($module, $index));
    Assert::assertStringContainsString(($source === 'index' ? '[news-index-conversion]' : '[news-anchor-conversion]').'SHORTCODE(', $actual['html']);
})->with(['index', 'anchor']);

it('retains the public module failure for an empty-array numeric index', function (string $name) use ($blockBaseCases): void {
    $case = $blockBaseCases[$name];
    $module = ['acf_fc_layout' => $name === 'news' ? 'modules_news_grid' : 'modules_uams_livewhale'] + $case['fields'];
    when('class_exists')->alias(static fn ($class) => $class === 'UAMS_Syndicate_News_Base' ? true : relay());
    when('get_stylesheet_directory')->justReturn(dirname(__DIR__, 2));
    $this->expectException(TypeError::class);
    $this->expectExceptionMessage('Unsupported operand types: array + int');
    BlockTemplateScenario::render($case, static fn () => uamswp_module($module, []));
})->with(['news', 'livewhale']);

it('releases an ephemeral fullscreen field before iframe markup', function () use ($blockBaseCases): void {
    $case = $blockBaseCases['iframe'];
    $values = $case['fields'];
    $variables = $case['variables'];
    $body = static function () use ($values, $variables): void {
        when('get_field')->alias(static function ($key) use ($values) {
            if ($key === 'iframe_allow_fullscreen') {
                return new class
                {
                    public function __destruct()
                    {
                        echo '[fullscreen-release]';
                    }
                };
            }

            Assert::assertArrayHasKey($key, $values);

            return $values[$key];
        });
        (static function (array $variables): void {
            extract($variables, EXTR_SKIP);
            require dirname(__DIR__, 2).'/blocks/iframe.php';
        })($variables);
    };
    $actual = BlockTemplateScenario::render($case, $body);
    Assert::assertTrue('[fullscreen-release]'.$case['expected']['html'] === $actual['html'], 'Fullscreen field lifetime changed.');
});
