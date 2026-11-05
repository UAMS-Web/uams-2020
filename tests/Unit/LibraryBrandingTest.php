<?php

declare(strict_types=1);

use PHPUnit\Framework\Assert;
use Uams2020\Tests\Support\LibraryBrandingScenario;

$libraryBrandingCases = [];
foreach (['header' => 'uamswp_site_image', 'footer' => 'uamswp_footer_creds_text'] as $kind => $function) {
    $inputs = json_decode(file_get_contents(__DIR__.'/../Fixtures/library-'.$kind.'-inputs.json'), true, flags: JSON_THROW_ON_ERROR);
    $outputs = json_decode(file_get_contents(__DIR__.'/../Fixtures/library-'.$kind.'-output.json'), true, flags: JSON_THROW_ON_ERROR);
    foreach ($inputs as $name => $input) {
        $libraryBrandingCases[$kind.' '.$name] = [$function, $input, $outputs[$name]];
    }
}

it('pins complete header and footer markup across site branding and option boundaries', function (string $function, array $input, string $expected): void {
    LibraryBrandingScenario::configure($input);
    $expected = str_replace('&copy; [YEAR]', '&copy; '.date('Y'), $expected);
    Assert::assertTrue($expected === LibraryBrandingScenario::render($function), 'Branding output changed for '.json_encode($input, JSON_THROW_ON_ERROR));
})->with($libraryBrandingCases);
