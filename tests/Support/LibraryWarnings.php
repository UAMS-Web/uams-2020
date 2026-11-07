<?php

declare(strict_types=1);

namespace Uams2020\Tests\Support;

use ErrorException;
use PHPUnit\Framework\Assert;
use ReflectionFunction;

/** Pins existing warnings without hiding other failures or depending on physical source lines. */
final class LibraryWarnings
{
    public static function capture(string|array|\Closure $function, string|array $message, callable $call)
    {
        $source = is_array($function) ? new \ReflectionMethod($function[0], $function[1]) : new ReflectionFunction($function);
        $warnings = [];
        $messages = (array) $message;
        set_error_handler(static function (int $severity, string $text, string $file, int $line) use ($source, $messages, &$warnings): bool {
            if ($severity !== E_WARNING || realpath($file) !== realpath($source->getFileName()) || $line < $source->getStartLine() || $line > $source->getEndLine() || ! in_array($text, $messages, true)) {
                throw new ErrorException($text, 0, $severity, $file, $line);
            }

            $warnings[] = $text;

            return true;
        });
        try {
            $result = $call();
        } finally {
            restore_error_handler();
        }

        Assert::assertSame($messages, $warnings);

        return $result;
    }
}
