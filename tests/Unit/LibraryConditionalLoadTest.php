<?php

declare(strict_types=1);

namespace Uams2020\Tests\Unit;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\PreserveGlobalState;
use PHPUnit\Framework\Attributes\RunTestsInSeparateProcesses;
use PHPUnit\Framework\TestCase;
use Uams2020\Tests\Support\ThemeBootstrap;

use function Brain\Monkey\Functions\when;
use function Brain\Monkey\setUp;
use function Brain\Monkey\tearDown;

#[RunTestsInSeparateProcesses]
#[PreserveGlobalState(false)]
final class LibraryConditionalLoadTest extends TestCase
{
    public static function contexts(): array
    {
        return [['institute', 'eye', true], ['uams', 'main', true], ['uamshealth', 'main', true], ['inside', 'main', true], ['uams', 'medicine', false], ['unknown', 'main', false]];
    }

    #[DataProvider('contexts')]
    public function test_quicklinks_callback_is_declared_only_for_custom_menu_sites(string $site, string $subsite, bool $expected): void
    {
        setUp();
        try {
            ThemeBootstrap::load();
            when('uams_get_site_info')->justReturn(['site' => $site, 'subsite' => $subsite]);
            require dirname(__DIR__, 2).'/lib/quicklinks.php';
            self::assertSame($expected, function_exists('quicklinks_menu'));
            if ($expected) {
                self::assertSame(dirname(__DIR__, 2).'/lib/quicklinks.php', (new \ReflectionFunction('quicklinks_menu'))->getFileName());
            }
        } finally {
            tearDown();
        }
    }
}
