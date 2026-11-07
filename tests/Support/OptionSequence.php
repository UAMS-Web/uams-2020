<?php

declare(strict_types=1);

namespace Uams2020\Tests\Support;

/** A serializable option object whose display value can change between reads. */
final class OptionSequence implements \Stringable
{
    private int $index = 0;

    public function __construct(private array $values) {}

    public function __toString(): string
    {
        return (string) $this->values[min($this->index++, count($this->values) - 1)];
    }
}
