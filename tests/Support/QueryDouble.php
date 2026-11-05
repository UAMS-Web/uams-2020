<?php

declare(strict_types=1);

namespace Uams2020\Tests\Support;

/** Only the constructor and loop boundary used by the theme; integration uses real WP_Query. */
final class QueryDouble
{
    public static array $nextRows = [];

    public static array $lastArgs = [];

    public array $args;

    private array $rows;

    private int $index = -1;

    public function __construct(array $args)
    {
        self::$lastArgs = $args;
        $this->args = $args;
        $this->rows = self::$nextRows;
    }

    public function have_posts(): bool
    {
        return isset($this->rows[$this->index + 1]);
    }

    public function the_post(): void
    {
        $GLOBALS['post'] = (object) $this->rows[++$this->index];
    }
}
