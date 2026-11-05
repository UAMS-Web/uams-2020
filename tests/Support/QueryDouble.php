<?php

declare(strict_types=1);

namespace Uams2020\Tests\Support;

/** Only the constructor and loop boundary used by the theme; integration uses real WP_Query. */
final class QueryDouble
{
    public static array $nextRows = [];

    public static array $lastArgs = [];

    public static array $history = [];

    public array $args;

    /** WP_Query exposes unknown ID through __get as null; thumbnail helpers then use the current post. */
    public $ID;

    private array $rows;

    private int $index = -1;

    private int $readsWithoutAdvance = 0;

    public function __construct(array $args)
    {
        self::$lastArgs = $args;
        self::$history[] = $args;
        $this->args = $args;
        $this->rows = self::$nextRows;
    }

    public function have_posts(): bool
    {
        if (++$this->readsWithoutAdvance > 100) {
            throw new \RuntimeException('Query loop did not advance.');
        }

        return isset($this->rows[$this->index + 1]);
    }

    public function the_post(): void
    {
        $this->readsWithoutAdvance = 0;
        $GLOBALS['post'] = (object) $this->rows[++$this->index];
    }
}
