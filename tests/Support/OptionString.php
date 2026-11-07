<?php

declare(strict_types=1);

namespace Uams2020\Tests\Support;

/** A serializable option value, as WordPress get_option() may return. */
final class OptionString implements \Stringable
{
    public function __construct(private string $value) {}

    public function __toString(): string
    {
        return $this->value;
    }
}
