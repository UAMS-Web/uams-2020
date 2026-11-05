<?php

declare(strict_types=1);
use Uams2020\Tests\Support\BlockRegistryDouble;

/** Unit-only core boundaries. Integration tests use WordPress' original classes. */
class WP_User
{
    public array $roles = [];

    public static array $constructed = [];

    public function __construct(public int $ID = 0)
    {
        self::$constructed[] = $this->ID;
    }
}

class_alias(BlockRegistryDouble::class, 'WP_Block_Type_Registry');

class WP_Widget
{
    public array $constructorArguments;

    public function __construct(...$args)
    {
        $this->constructorArguments = $args;
    }

    public function get_field_id($field)
    {
        return 'FIELD-ID:'.$field;
    }

    public function get_field_name($field)
    {
        return 'FIELD-NAME:'.$field;
    }
}

class Walker_Page
{
    public array $db_fields = ['id' => 'ID'];

    public array $delegated = [];

    public function display_element($element, &$children, $maxDepth, $depth, $args, &$output)
    {
        $this->delegated[] = [$element, $children, $maxDepth, $depth, $args, $output];
        $output .= '[PARENT]';
    }
}

class Walker_Nav_Menu {}

class WP_Error
{
    public array $arguments;

    public function __construct(...$args)
    {
        $this->arguments = $args;
    }
}
