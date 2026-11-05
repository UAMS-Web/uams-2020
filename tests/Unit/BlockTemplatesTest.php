<?php

declare(strict_types=1);

use PHPUnit\Framework\Assert;
use Uams2020\Tests\Support\BlockTemplateScenario;
use Uams2020\Tests\Support\TraceString;

use function Brain\Monkey\Functions\when;

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

it('preserves native phone conversions even when the CTA hides its action', function () use ($blockBaseCases): void {
    $case = $blockBaseCases['cta'];
    $case['native_phone'] = true;
    $case['fields']['cta_bar_action_type'] = 'none';
    $case['fields']['cta_bar_phone'] = new TraceString('5015550123', '[phone-conversion]');
    $actual = BlockTemplateScenario::render($case);
    Assert::assertStringStartsWith('[phone-conversion][phone-conversion]', $actual['html']);
    Assert::assertSame(2, substr_count($actual['html'], '[phone-conversion]'));
    Assert::assertStringContainsString('no-link', $actual['html']);
});
