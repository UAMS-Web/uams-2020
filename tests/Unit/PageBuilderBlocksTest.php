<?php

declare(strict_types=1);

use Brain\Monkey\Functions;
use Uams2020\Tests\Support\PageBuilderLoader;

use function Brain\Monkey\Functions\when;

it('limits section children by role and lets the filter replace the list', function (bool $admin): void {
    Functions\expect('current_user_can')->once()->with('manage_options')->andReturn($admin);
    $expected = ['core/heading', 'core/paragraph', 'core/embed', 'core/list', 'core/list-item', 'core/quote', 'core/image', 'core/shortcode', 'core/table', 'core/file', 'gravityforms/form', 'formidable/simple-form'];
    if ($admin) {
        $expected[] = 'acf/uams-iframe';
    }

    Functions\expect('apply_filters')->once()->with('uamswp_section_allowed_blocks', $expected)->andReturn(['custom/block']);
    expect(uamswp_section_allowed_blocks())->toBe(['custom/block']);
})->with([false, true]);

it('returns the permission check for the ACF menu regardless of the incoming flag', function (bool $admin, $incoming): void {
    Functions\expect('current_user_can')->once()->with('manage_options')->andReturn($admin);
    expect(my_acf_show_admin($incoming))->toBe($admin);
})->with([[false, true], [true, false], [false, null]]);

it('handles placeholder boundaries and escapes both displayed strings', function ($block, $preview, bool $shown, string $title): void {
    when('__')->alias(static fn ($value): string => 'translated:'.$value);
    when('esc_html')->alias(static fn ($value): string => htmlspecialchars((string) $value, ENT_QUOTES));
    when('esc_html__')->alias(static fn ($value): string => htmlspecialchars('translated:'.$value, ENT_QUOTES));
    ob_start();
    $actual = uamswp_block_placeholder($block, $preview);
    $html = ob_get_clean();
    expect($actual)->toBe($shown)->and($html)->toBe($shown ? '<div class="uams-block-placeholder" style="padding:1.5rem;border:1px dashed currentColor;text-align:center;"><strong>'.$title.'</strong><br>translated:Click the pencil icon in the block toolbar to add content.</div>' : '');
})->with([
    [null, true, false, ''], [[], true, false, ''], ['bad', true, false, ''],
    [['title' => 'Name'], false, false, ''], [['title' => 'Name'], true, true, 'Name'],
    [['title' => '<name>&', 'data' => ['_field' => 'field_key', 'body' => '', 'null' => null, 'array' => [], 'false' => false, 'zero' => '0']], true, true, '&lt;name&gt;&amp;'],
    [['title' => '', 'data' => null], true, true, 'translated:UAMS block'],
    [['title' => 0, 'data' => 'invalid'], true, true, 'translated:UAMS block'],
    [['data' => [0 => 'content']], true, false, ''],
    [['data' => ['body' => 'content']], true, false, ''],
    [['data' => ['body' => 0]], true, false, ''],
    [['data' => ['body' => true]], true, false, ''],
    [['data' => ['body' => ['nested']]], true, false, ''],
    [['data' => ['a_field' => 'field_key']], true, false, ''],
]);

it('uses empty defaults when a placeholder is called outside block context', function (): void {
    ob_start();
    expect(uamswp_block_placeholder())->toBeFalse();
    expect(ob_get_clean())->toBe('');
});

it('does not treat a supplied block as an editor preview unless preview is requested', function (): void {
    when('esc_html')->returnArg();
    when('esc_html__')->returnArg();
    ob_start();
    $shown = uamswp_block_placeholder(['title' => 'Named block', 'data' => []]);
    $html = ob_get_clean();
    expect($shown)->toBeFalse()->and($html)->toBe('');
});

it('checks HTTPS and host boundaries using the filtered allowlist', function ($url, $hosts, bool $allowed): void {
    when('wp_parse_url')->alias(parse_url(...));
    when('apply_filters')->alias(static function ($hook, $value) use ($hosts) {
        expect($hook)->toBe('uamswp_iframe_allowed_hosts')->and($value)->toBe([]);

        return $hosts;
    });
    expect(uamswp_iframe_url_is_allowed($url))->toBe($allowed);
})->with([
    ['https://example.test/path', [], true], ['HTTPS://EXAMPLE.TEST/path', ['example.test'], true],
    ['https://example.test/path', ['EXAMPLE.TEST'], true], ['https://example.test/path', 'example.test', true],
    ['https://sub.example.test', ['example.test'], false], ['https://example.test.evil.test', ['example.test'], false],
    ['https://other.test', ['example.test'], false], ['http://example.test', [], false],
    ['//example.test/path', [], false], ['/path', [], false], ['https:///path', [], false],
    ['https://user:pass@example.test:443/path?q=1', ['example.test'], true],
]);

it('preserves earlier ACF validation results and validates only nonempty valid fields', function ($valid, $value, $hosts, $expected): void {
    when('__')->alias(static fn ($text): string => 'translated:'.$text);
    when('wp_parse_url')->alias(parse_url(...));
    when('apply_filters')->justReturn($hosts);

    $callback = PageBuilderLoader::$hooks[3][2];
    expect($callback($valid, $value))->toBe($expected);
})->with([
    [true, '', [], true], ['earlier error', 'http://example.test', [], 'earlier error'],
    [false, 'https://example.test', [], false], [1, 'http://example.test', [], 1],
    [true, 'https://example.test', [], true],
    [true, 'http://example.test', [], 'translated:Use an https:// address on an approved host.'],
    [true, 'https://other.test', ['example.test'], 'translated:Use an https:// address on an approved host.'],
]);

it('hides all four iframe fields from non-administrators and preserves the full administrator field', function (bool $admin): void {
    Functions\expect('current_user_can')->times(4)->with('manage_options')->andReturn($admin);
    foreach (array_slice(PageBuilderLoader::$hooks, 4) as $hook) {
        expect(($hook[2])(['key' => 'existing', 'value' => '<value>']))->toBe($admin ? ['key' => 'existing', 'value' => '<value>'] : false);
    }
})->with([false, true]);
