<?php

declare(strict_types=1);

namespace Uams2020\Tests\Unit;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\PreserveGlobalState;
use PHPUnit\Framework\Attributes\RunTestsInSeparateProcesses;
use PHPUnit\Framework\TestCase;
use Uams2020\Tests\Support\ThemeBootstrap;

use function Brain\Monkey\Functions\expect;
use function Brain\Monkey\setUp;
use function Brain\Monkey\tearDown;

#[RunTestsInSeparateProcesses]
#[PreserveGlobalState(false)]
final class DebugFallbackTest extends TestCase
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

    public static function debugContexts(): array
    {
        return [
            'debug disabled' => [false, 'missing', 0],
            'debug enabled' => [true, 'missing', 1],
            'requested default' => [true, 'full', 0],
        ];
    }

    #[DataProvider('debugContexts')]
    public function test_missing_size_logs_only_when_debugging_another_size(bool $debug, string $requested, int $logs): void
    {
        define('WP_DEBUG', $debug);
        expect('wp_get_attachment_image_src')->once()->with(42, $requested)->andReturn(false);
        expect('wp_get_attachment_image_src')->once()->with(42, 'full')->andReturn(['full.jpg', 400, 200]);
        expect('error_log')->times($logs)->with("Image Sizer: Fallback size '$requested' not found for attachment ID 42. Using 'full'.");
        self::assertSame('full.jpg', image_sizer_get_fallback(42, $requested));
    }
}
