<?php

declare(strict_types=1);

namespace Uams2020\Tests\Support;

use PHPUnit\Framework\Assert;

use function Brain\Monkey\Functions\when;
use function Patchwork\redefine;
use function Patchwork\relay;

final class LibraryPageScenario
{
    public static function configure(array $fields, bool $resizer): void
    {
        when('get_the_id')->justReturn(41);
        when('get_field')->alias(static function ($key, $id) use ($fields) {
            Assert::assertSame(41, $id);
            Assert::assertArrayHasKey($key, $fields);

            return $fields[$key];
        });
        when('function_exists')->alias(static fn ($name) => $name === 'bis_get_attachment_image' ? $resizer : relay());
        when('image_sizer')->alias(static fn (...$args): string => 'https://image.test/'.implode('/', $args));
        // Core accepts only its first argument. Legacy extra size arguments have no effect.
        when('wp_get_attachment_url')->alias(static fn ($id): string => 'https://attachment.test/'.$id);
    }

    public static function options($option): array
    {
        when('get_the_id')->justReturn(41);
        when('get_field')->alias(static function ($key, $id) use ($option) {
            Assert::assertSame('page_title_options', $key);
            Assert::assertSame(41, $id);

            return $option;
        });
        $calls = [];
        foreach (['add_action', 'add_filter', 'remove_action'] as $function) {
            redefine($function, static function ($name, $callback, $priority = 10, $accepted = 1) use ($function, &$calls) {
                $calls[] = [$function, $name, $callback, $priority, $accepted];

                return relay();
            });
        }

        page_options();

        return $calls;
    }
}
