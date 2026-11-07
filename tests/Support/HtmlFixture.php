<?php

declare(strict_types=1);

namespace Uams2020\Tests\Support;

use PHPUnit\Framework\Assert;

final class HtmlFixture
{
    public static function assertSearch(string $name, string $actual): void
    {
        $expected = file_get_contents(__DIR__.'/../Fixtures/search-output/'.$name.'.html');
        Assert::assertIsString($expected);
        Assert::assertSame(str_replace("\r\n", "\n", $expected), str_replace("\r\n", "\n", $actual));
    }
}
