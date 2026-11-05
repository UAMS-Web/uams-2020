<?php

declare(strict_types=1);

use PHPUnit\Framework\Assert;
use Uams2020\Tests\Support\BlockTemplateScenario;

$blockTemplateCases = json_decode(file_get_contents(__DIR__.'/../Fixtures/block-templates.json'), true, flags: JSON_THROW_ON_ERROR);

it('pins block markup and external boundary arguments', function (array $case): void {
    $actual = BlockTemplateScenario::render($case);
    Assert::assertTrue($case['expected'] === $actual, 'Block output or calls changed: '.$case['name'].' / '.$case['label']);
})->with(array_combine(array_map(static fn (array $case): string => $case['name'].' / '.$case['label'], $blockTemplateCases), array_map(static fn (array $case): array => [$case], $blockTemplateCases)));
