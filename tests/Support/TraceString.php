<?php

declare(strict_types=1);

namespace Uams2020\Tests\Support;

/** A caller value whose real string conversion has visible output, with ordinary string offsets. */
final class TraceString implements \ArrayAccess, \Stringable
{
    public function __construct(private readonly string $value, private readonly string $marker) {}

    public function __toString(): string
    {
        echo $this->marker;

        return $this->value;
    }

    public function offsetExists(mixed $offset): bool
    {
        return isset($this->value[$offset]);
    }

    public function offsetGet(mixed $offset): mixed
    {
        return $this->value[$offset] ?? null;
    }

    public function offsetSet(mixed $offset, mixed $value): void
    {
        throw new \LogicException('Read-only caller value.');
    }

    public function offsetUnset(mixed $offset): void
    {
        throw new \LogicException('Read-only caller value.');
    }
}
