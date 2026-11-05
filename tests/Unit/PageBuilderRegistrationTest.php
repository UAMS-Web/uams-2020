<?php

declare(strict_types=1);

namespace Uams2020\Tests\Unit;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\PreserveGlobalState;
use PHPUnit\Framework\Attributes\RunTestsInSeparateProcesses;
use PHPUnit\Framework\TestCase;
use Uams2020\Tests\Support\PageBuilderLoader;
use Uams2020\Tests\Support\SearchTemplateLoader;
use Uams2020\Tests\Support\ThemeBootstrap;

use function Brain\Monkey\Functions\when;
use function Brain\Monkey\setUp;
use function Brain\Monkey\tearDown;
use function Patchwork\redefine;
use function Patchwork\relay;

#[RunTestsInSeparateProcesses]
#[PreserveGlobalState(false)]
final class PageBuilderRegistrationTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        setUp();
        ThemeBootstrap::load();
    }

    protected function tearDown(): void
    {
        $this->addToAssertionCount(\Mockery::getContainer()->mockery_getExpectationCount());
        tearDown();
        parent::tearDown();
    }

    public static function pluginStates(): array
    {
        return [
            'no ACF functions' => [false, false, false, false, false],
            'options only' => [true, false, false, false, false],
            'fields only' => [false, true, false, false, false],
            'blocks only' => [false, false, true, true, true],
            'all without optional plugins' => [true, true, true, false, false],
            'all with optional plugins' => [true, true, true, true, true],
            'mask fields without news' => [true, true, true, true, false],
            'news without mask fields' => [true, true, true, false, true],
        ];
    }

    #[DataProvider('pluginStates')]
    public function test_registers_exact_options_fields_blocks_and_hooks(bool $optionsAvailable, bool $fieldsAvailable, bool $blocksAvailable, bool $maskAvailable, bool $newsAvailable): void
    {
        when('__')->alias(static fn ($text, $domain = 'default'): string => 'translated:'.$domain.':'.$text);
        when('get_stylesheet_directory')->justReturn(dirname(__DIR__, 2));

        $availability = ['acf_add_options_page' => $optionsAvailable, 'acf_add_local_field_group' => $fieldsAvailable, 'acf_register_block_type' => $blocksAvailable];
        when('class_exists')->alias(static fn ($name) => match ($name) {
            'po_acf_plugin_maskfield' => $maskAvailable,
            'UAMS_Syndicate_News_Base' => $newsAvailable,
            default => relay(),
        });
        $actual = ['groups' => [], 'options' => [], 'blocks' => []];
        foreach (['acf_add_local_field_group' => 'groups', 'acf_add_options_page' => 'options', 'acf_register_block_type' => 'blocks'] as $function => $bucket) {
            when($function)->alias(static function ($arguments) use (&$actual, $bucket): void {
                $actual[$bucket][] = $arguments;
            });
        }

        when('function_exists')->alias(static fn ($name) => array_key_exists($name, $availability) ? $availability[$name] : relay());

        $hooks = [];
        foreach (['add_action' => 'action', 'add_filter' => 'filter'] as $function => $type) {
            redefine($function, static function ($name, $callback, $priority = 10, $accepted = 1) use (&$hooks, $type): void {
                $hooks[] = [$type, $name, $callback, $priority, $accepted];
            });
        }

        require dirname(__DIR__, 2).'/lib/meta-box.php';
        require dirname(__DIR__, 2).'/lib/blocks.php';
        require dirname(__DIR__, 2).'/lib/acf.php';
        uams_register_blocks();
        uamswp_iframe_block_fields();

        $expected = json_decode((string) file_get_contents(__DIR__.'/../Fixtures/page-builder-registrations'.($newsAvailable ? '' : '-without-plugins').'.json'), true, flags: JSON_THROW_ON_ERROR);
        $setPhoneType = static function (array $items) use (&$setPhoneType, $maskAvailable): array {
            foreach ($items as $index => $item) {
                if (! is_array($item)) {
                    continue;
                }

                if (in_array($item['key'] ?? null, ['field_primary_phone_number', 'field_secondary_phone_number'], true)) {
                    $item['type'] = $maskAvailable ? 'maskfield' : 'text';
                }

                $items[$index] = $setPhoneType($item);
            }

            return $items;
        };
        $expected['groups'] = $setPhoneType($expected['groups']);
        if (! $optionsAvailable) {
            $expected['options'] = [];
        }

        if (! $fieldsAvailable) {
            $expected['groups'] = [];
        }

        if (! $blocksAvailable) {
            $expected['blocks'] = [];
        }

        self::assertSame($expected, $actual);
        $expectedHooks = [
            ['filter', 'acf/settings/show_admin', 'my_acf_show_admin', 10, 1],
            ['action', 'acf/init', 'uams_register_blocks', 10, 1],
            ['action', 'acf/init', 'uamswp_iframe_block_fields', 10, 1],
            ['filter', 'acf/validate_value/key=field_iframe_url_b', 'Closure', 10, 2],
        ];
        foreach (['url', 'title', 'height', 'fullscreen'] as $key) {
            $expectedHooks[] = ['filter', 'acf/prepare_field/key=field_iframe_'.$key.'_b', 'Closure', 10, 1];
        }

        self::assertSame($expectedHooks, array_map(static function (array $hook): array {
            $hook[2] = $hook[2] instanceof \Closure ? 'Closure' : $hook[2];

            return $hook;
        }, $hooks));
    }

    public function test_load_time_capture_does_not_collect_later_search_hooks(): void
    {
        PageBuilderLoader::load();
        $before = PageBuilderLoader::$hooks;
        SearchTemplateLoader::load();
        self::assertCount(8, $before);
        self::assertSame($before, PageBuilderLoader::$hooks);
        self::assertFalse(function_exists('acf_add_local_field_group'));
    }
}
