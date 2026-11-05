<?php

declare(strict_types=1);

use PHPUnit\Framework\Assert;
use Uams2020\Tests\Support\LibraryBrandingScenario;
use Uams2020\Tests\Support\OptionSequence;
use Uams2020\Tests\Support\OptionString;

$libraryBrandingCases = [];
foreach (['header' => 'uamswp_site_image', 'footer' => 'uamswp_footer_creds_text'] as $kind => $function) {
    $inputs = json_decode(file_get_contents(__DIR__.'/../Fixtures/library-'.$kind.'-inputs.json'), true, flags: JSON_THROW_ON_ERROR);
    $outputs = json_decode(file_get_contents(__DIR__.'/../Fixtures/library-'.$kind.'-output.json'), true, flags: JSON_THROW_ON_ERROR);
    foreach ($inputs as $name => $input) {
        $libraryBrandingCases[$kind.' '.$name] = [$function, $input, $outputs[$name]];
    }
}

/**
 * lib/footer.php: IncrementInteger and DecrementInteger on the initial custom-address count are
 * equivalent. A populated address list overwrites the count before use; otherwise -1, 0 and 1 all
 * take the same less-than-two branch.
 */
it('pins complete header and footer markup across site branding and option boundaries', function (string $function, array $input, string $expected): void {
    LibraryBrandingScenario::configure($input);
    $expected = str_replace('&copy; [YEAR]', '&copy; '.date('Y'), $expected);
    Assert::assertTrue($expected === LibraryBrandingScenario::renderCase($function, $input), 'Branding output changed for '.json_encode($input, JSON_THROW_ON_ERROR));
})->with($libraryBrandingCases);

$stringableBrandingCases = [];
foreach ($libraryBrandingCases as $name => [$function, $input, $expected]) {
    foreach (['site', 'subsite', 'department'] as $key) {
        if (! is_string($input['site'][$key])) {
            continue;
        }

        if (($key === 'site' && $input['site'][$key] === '') || ($key === 'subsite' && (($input['site']['site'] === 'institute' && $input['site'][$key] === 'uams') || ($function === 'uamswp_site_image' && $input['site']['site'] === 'inside' && $input['site'][$key] === 'main')))) {
            continue;
        }

        $copy = $input;
        $copy['site'][$key] = new OptionString($copy['site'][$key]);
        $stringableBrandingCases[$name.' object '.$key] = [$function, $copy, $expected];
    }

    if (isset($input['fields']['uamswp_secondary_number_type'])) {
        $copy = $input;
        $copy['fields']['uamswp_secondary_number_type'] = new OptionString($copy['fields']['uamswp_secondary_number_type']);
        $stringableBrandingCases[$name.' secondary type object'] = [$function, $copy, $expected];
    }
}

it('preserves stringable site identity and ACF option values at the branding boundary', function (string $function, array $input, string $expected): void {
    LibraryBrandingScenario::configure($input);
    $expected = str_replace('&copy; [YEAR]', '&copy; '.date('Y'), $expected);
    Assert::assertTrue($expected === LibraryBrandingScenario::renderCase($function, $input), 'Stringable branding output changed for '.json_encode($input, JSON_THROW_ON_ERROR));
})->with($stringableBrandingCases);

it('preserves changing option display values when choosing institute social links', function (): void {
    $subsite = unserialize(serialize(new OptionSequence(['', '', '', 'institute_eye'])));
    LibraryBrandingScenario::configure(['site' => ['site' => 'institute', 'subsite' => $subsite, 'department' => 'main']]);
    $output = LibraryBrandingScenario::render('uamswp_footer_creds_text');
    expect($output)->toContain('href="https://www.facebook.com/UAMShealth"')->not->toContain('https://www.facebook.com/UAMSJonesEye');
});
