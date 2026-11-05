<?php

declare(strict_types=1);

use Uams2020\Tests\Support\OptionString;

use function Brain\Monkey\Functions\when;
use function Patchwork\relay;

it('pins site identity for missing, empty and configured ACF options', function (bool $acfAvailable, array $options, array $expected): void {
    when('class_exists')->alias(static fn ($class) => $class === 'acf' ? $acfAvailable : relay());
    when('get_option')->alias(static fn ($key) => $options[$key] ?? false);

    expect(uams_get_site_info())->toBe($expected);
})->with([
    'ACF absent' => [false, [], ['site' => 'uams', 'subsite' => 'uams', 'department' => 'uams']],
    'unconfigured' => [true, [], ['site' => '', 'subsite' => '', 'department' => '']],
    'truthy template keeps loose matching' => [true, ['options_uamswp_template' => true], ['site' => 'uamshealth', 'subsite' => 'uams', 'department' => '']],
    'health defaults' => [true, ['options_uamswp_template' => 'health'], ['site' => 'uamshealth', 'subsite' => 'uams', 'department' => '']],
    'health main' => [true, ['options_uamswp_template' => 'health', 'options_uamswp_uamshealth_subsite' => 'main'], ['site' => 'uamshealth', 'subsite' => 'main', 'department' => '']],
    'inside defaults' => [true, ['options_uamswp_template' => 'inside'], ['site' => 'inside', 'subsite' => 'uams', 'department' => '']],
    'inside subsite' => [true, ['options_uamswp_template' => 'inside', 'options_uamswp_inside_subsite' => 'employees'], ['site' => 'inside', 'subsite' => 'employees', 'department' => '']],
    'institute defaults' => [true, ['options_uamswp_template' => 'institute'], ['site' => 'institute', 'subsite' => 'uams', 'department' => '']],
    'institute without department' => [true, ['options_uamswp_template' => 'institute', 'options_uamswp_institute' => 'institute_eye'], ['site' => 'institute', 'subsite' => 'institute_eye', 'department' => 'uams']],
    'uams defaults' => [true, ['options_uamswp_template' => 'uams'], ['site' => 'uams', 'subsite' => 'uams', 'department' => '']],
    'campus defaults' => [true, ['options_uamswp_template' => 'uams', 'options_uamswp_location' => 'uams'], ['site' => 'uams', 'subsite' => 'uams', 'department' => '']],
    'regional defaults' => [true, ['options_uamswp_template' => 'uams', 'options_uamswp_location' => 'regional-campus'], ['site' => 'uams', 'subsite' => 'regional-campus', 'department' => '']],
    'regional campus' => [true, ['options_uamswp_template' => 'uams', 'options_uamswp_location' => 'regional-campus', 'options_uamswp_regional_campus' => 'northwest'], ['site' => 'uams', 'subsite' => 'regional-campus', 'department' => 'northwest']],
    'other location' => [true, ['options_uamswp_template' => 'uams', 'options_uamswp_location' => 'other'], ['site' => 'uams', 'subsite' => 'other', 'department' => '']],
    'college defaults' => [true, ['options_uamswp_template' => 'uams', 'options_uamswp_location' => 'uams', 'options_uamswp_uams_subsite' => 'medicine'], ['site' => 'uams', 'subsite' => 'medicine', 'department' => 'uams']],
    ...array_map(static fn (string $institute): array => [true, ['options_uamswp_template' => 'institute', 'options_uamswp_institute' => 'institute_'.$institute, 'options_uamswp_institute_'.$institute.'_dept' => 'department'], ['site' => 'institute', 'subsite' => 'institute_'.$institute, 'department' => 'department']], ['aging', 'eye', 'spine', 'digi-health', 'pri', 'tri', 'cancer', 'ichi']),
    ...array_map(static fn (array $college): array => [true, ['options_uamswp_template' => 'uams', 'options_uamswp_location' => 'uams', 'options_uamswp_uams_subsite' => $college[0], 'options_uamswp_uams_'.$college[1].'_dept' => 'department'], ['site' => 'uams', 'subsite' => $college[0], 'department' => 'department']], [['health-prof', 'cohp'], ['medicine', 'com'], ['nursing', 'con'], ['pharmacy', 'cop'], ['public-health', 'coph'], ['grad-school', 'grad-school'], ['other', 'other']]),
]);

it('pins GeoIP presence without contacting the external service', function (bool $available): void {
    when('function_exists')->alias(static fn ($function) => $function === 'geoip_detect2_get_external_ip_adress' ? $available : relay());
    $value = $available ? 'true' : 'false';
    expect(uamswp_force_geo('old', 42, []))->toBe($value)
        ->and(uamswp_set_geo(['value' => 'old', 'label' => 'Geo']))->toBe(['value' => $value, 'label' => 'Geo'])
        ->and(uamswp_set_geo(['label' => 'Geo']))->toBe(['label' => 'Geo']);
})->with([true, false]);

it('preserves stringable option values while selecting site and department branches', function (array $values, string $site, string $subsiteKey, ?string $departmentKey): void {
    $options = array_map(static fn ($value): OptionString => new OptionString($value), $values);
    when('class_exists')->alias(static fn ($class) => $class === 'acf' ? true : relay());
    when('get_option')->alias(static fn ($key) => $options[$key] ?? false);

    expect(uams_get_site_info())->toBe(['site' => $site, 'subsite' => $options[$subsiteKey], 'department' => $departmentKey === null ? '' : $options[$departmentKey]]);
})->with([
    [['options_uamswp_template' => 'health', 'options_uamswp_uamshealth_subsite' => 'main'], 'uamshealth', 'options_uamswp_uamshealth_subsite', null],
    [['options_uamswp_template' => 'inside', 'options_uamswp_inside_subsite' => 'main'], 'inside', 'options_uamswp_inside_subsite', null],
    ...array_map(static fn (string $name): array => [
        ['options_uamswp_template' => 'institute', 'options_uamswp_institute' => 'institute_'.$name, 'options_uamswp_institute_'.$name.'_dept' => 'department'],
        'institute', 'options_uamswp_institute', 'options_uamswp_institute_'.$name.'_dept',
    ], ['aging', 'eye', 'spine', 'digi-health', 'pri', 'tri', 'cancer', 'ichi']),
    ...array_map(static fn (array $college): array => [
        ['options_uamswp_template' => 'uams', 'options_uamswp_location' => 'uams', 'options_uamswp_uams_subsite' => $college[0], 'options_uamswp_uams_'.$college[1].'_dept' => 'department'],
        'uams', 'options_uamswp_uams_subsite', 'options_uamswp_uams_'.$college[1].'_dept',
    ], [['health-prof', 'cohp'], ['medicine', 'com'], ['nursing', 'con'], ['pharmacy', 'cop'], ['public-health', 'coph'], ['grad-school', 'grad-school'], ['other', 'other']]),
]);

it('retains a truthy raw subsite and department from WordPress options', function (): void {
    $options = ['options_uamswp_template' => 'uams', 'options_uamswp_location' => 'uams', 'options_uamswp_uams_subsite' => true, 'options_uamswp_uams_cohp_dept' => 'department'];
    when('class_exists')->alias(static fn ($class) => $class === 'acf' ? true : relay());
    when('get_option')->alias(static fn ($key): bool|string => $options[$key] ?? false);

    expect(uams_get_site_info())->toBe(['site' => 'uams', 'subsite' => true, 'department' => 'department']);
});
