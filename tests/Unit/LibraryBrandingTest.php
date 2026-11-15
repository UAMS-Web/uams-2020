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

it('reads a header subsection ID that is a string as an integer', function (): void {
    LibraryBrandingScenario::configure(['site' => ['site' => 'uamshealth', 'subsite' => 'main', 'department' => 'main'], 'subsection' => '7']);
    $html = LibraryBrandingScenario::render('uamswp_site_image');
    expect($html)->toContain('<a class="title" href="/page/7">PAGE:7</a>');
});

it('treats a header subsection that is not an ID as no subsection', function ($subsection): void {
    LibraryBrandingScenario::configure(['site' => ['site' => 'uamshealth', 'subsite' => 'main', 'department' => 'main'], 'subsection' => $subsection]);
    expect(LibraryBrandingScenario::render('uamswp_site_image'))->toContain('class="navbar-brand no-subbrand"');
})->with([[null], [false], [''], [['7']], ['0']]);

it('renders an empty header title when the site title is not text', function ($title): void {
    LibraryBrandingScenario::configure(['site' => ['site' => 'uams', 'subsite' => 'uams', 'department' => 'uams'], 'title' => $title]);
    expect(LibraryBrandingScenario::render('uamswp_site_image'))->toContain('href="URL:https://site.test/"></a>');
})->with([[['Title']], [new stdClass]]);

it('splits the root title from a site title that has no three-space marker without failing', function (): void {
    LibraryBrandingScenario::configure([
        'site' => ['site' => 'institute', 'subsite' => 'eye', 'department' => 'main'],
        'root_title' => 'Donor   Institute', 'title' => 'Plain',
    ]);
    expect(LibraryBrandingScenario::render('uamswp_site_image'))->toContain('<span class="title-descriptor"></span> <span class="title-function">in</span>');
});

it('reads footer address lists and items that are not what ACF stores', function (array $fields, array $contains): void {
    LibraryBrandingScenario::configure(['site' => ['site' => 'institute', 'subsite' => 'eye', 'department' => 'dept'], 'fields' => $fields]);
    $html = LibraryBrandingScenario::render('uamswp_footer_creds_text');
    foreach ($contains as $snippet) {
        expect($html)->toContain($snippet);
    }
})->with([
    'list is text' => [['uamswp_address' => 'text'], ['<strong class="sr-only">Mailing Address:</strong> ', '4301 West Markham Street']],
    'list is true' => [['uamswp_address' => true], ['<strong class="sr-only">Mailing Address:</strong> ']],
    'items are not arrays' => [['uamswp_address' => ['text', null, false]], ['<strong>Mailing Address:</strong> ', '<span itemprop="addressLocality">Little Rock</span>']],
    'item fields are not text' => [
        ['uamswp_address' => [['address_title' => ['x'], 'address_street_1' => null, 'address_street_2' => ['y'], 'address_city' => false, 'address_state' => ['z'], 'address_zip' => null], ['address_title' => 'Two']]],
        ['<strong>Mailing Address:</strong> ', '<span itemprop="addressLocality">Little Rock</span>', '<span itemprop="addressRegion">AR</span>', '<span itemprop="postalCode">72205</span>', '>Two:</strong>'],
    ],
    'numeric zip' => [['uamswp_address' => [['address_zip' => 99999], ['address_title' => 'Two']]], ['<span itemprop="postalCode">99999</span>']],
]);

it('reads footer phone fields that are not text as their defaults', function (array $fields, array $contains, array $absent): void {
    LibraryBrandingScenario::configure(['site' => ['site' => 'uams', 'subsite' => 'uams', 'department' => 'uams'], 'fields' => $fields]);
    $html = LibraryBrandingScenario::render('uamswp_footer_creds_text');
    foreach ($contains as $snippet) {
        expect($html)->toContain($snippet);
    }
    foreach ($absent as $snippet) {
        expect($html)->not->toContain($snippet);
    }
})->with([
    'primary fields are arrays' => [
        ['uamswp_primary_phone_text' => ['x'], 'uamswp_primary_phone_number' => ['y']],
        ['>Phone:</span> ', 'tel:dash:501-686-7000', 'us:501-686-7000'], [],
    ],
    'link is text' => [['uamswp_secondary_number_type' => 'link', 'uamswp_secondary_link_url' => 'https://x.test'], [], ['more-phone']],
    'link has no url' => [['uamswp_secondary_number_type' => 'link', 'uamswp_secondary_link_url' => ['title' => 'x'], 'uamswp_secondary_link_text' => ['x']], ['class="more-phone" href="URL:">Additional Phone Numbers</a>'], []],
    'link url is an array' => [['uamswp_secondary_number_type' => 'link', 'uamswp_secondary_link_url' => ['url' => ['x']]], ['class="more-phone" href="URL:">'], []],
    'secondary phone is an array' => [['uamswp_secondary_number_type' => 'phone', 'uamswp_secondary_phone_number' => ['555']], [], ['footer-phone-label-2']],
    'secondary type is an array' => [['uamswp_secondary_number_type' => ['link'], 'uamswp_secondary_link_url' => ['url' => '/x']], [], ['more-phone']],
]);

