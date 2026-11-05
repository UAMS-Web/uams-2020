<?php

declare(strict_types=1);

use Brain\Monkey\Functions;
use PHPUnit\Framework\Assert;

use function Brain\Monkey\Functions\when;

it('pins the full plugin registration payload including translated notices', function (): void {
    when('__')->alias(static fn ($text, $domain = 'default'): string => 'T:'.$domain.':'.$text);
    when('_n_noop')->alias(static fn (...$args): array => ['plural' => $args]);
    when('get_stylesheet_directory')->justReturn('/theme');

    $expected = json_decode(file_get_contents(__DIR__.'/../Fixtures/library-plugin-registration.json'), true, flags: JSON_THROW_ON_ERROR);
    Functions\expect('tgmpa')->once()->withArgs(static function (...$actual) use ($expected): bool {
        Assert::assertTrue($expected === $actual, 'Plugin registration changed.');

        return true;
    });
    uamswp_do_plugins_register();
});

it('pins every favicon asset, size and metadata value', function (): void {
    when('get_stylesheet_directory_uri')->justReturn('https://theme.test');
    ob_start();
    uamswp_add_favicon();
    expect(ob_get_clean())->toBe(file_get_contents(__DIR__.'/../Fixtures/library-favicon.html'));
});

it('suppresses updates for exactly the two bundled SEO plugins', function (): void {
    $value = (object) ['response' => ['uamswp-seopress/seopress.php' => 'one', 'uamswp-seopress-pro/seopress-pro.php' => 'two', 'other/plugin.php' => 'kept'], 'other' => 'kept'];
    expect(disable_plugin_updates($value))->toBe($value)->and($value)->toEqual((object) ['response' => ['other/plugin.php' => 'kept'], 'other' => 'kept']);
    $without = (object) ['response' => ['other/plugin.php' => 'kept']];
    expect(disable_plugin_updates($without))->toBe($without)->and(disable_plugin_updates(null))->toBeNull()->and(disable_plugin_updates(['unchanged']))->toBe(['unchanged']);
    $absent = new stdClass;
    expect(disable_plugin_updates($absent))->toBe($absent);
});
