<?php

declare(strict_types=1);

namespace Uams2020\PhpStan;

/**
 * Maps ACF field names to the PHPDoc type `get_field()` returns for them, read from the
 * field definitions in `acf_fields/`.
 */
final class AcfFieldTypes
{
    /**
     * @return array<string, string> Field name to PHPDoc type; a name defined twice with different types is left out.
     */
    public static function load(string $directory): array
    {
        $types = [];
        $conflicts = [];
        $files = glob(rtrim($directory, '/').'/*.php');
        foreach ($files === false ? [] : $files as $file) {
            self::collect(self::definitions($file), $types, $conflicts);
        }

        return array_diff_key($types, $conflicts);
    }

    /**
     * The field definitions a file returns. The files read include variables the caller supplies
     * (the key suffix and nested field groups); empty values make every nested group contribute nothing.
     *
     * @return array<mixed>
     */
    private static function definitions(string $file): array
    {
        $suffix = '';
        $content = $action_bar = $call_out = $cta = $hero = $link_list = $news = [];
        $overlay = $post_tile = $post_tiles = $side_by_side = $stacked = $livewhale = $gallery = [];
        $definitions = require $file;

        return is_array($definitions) ? $definitions : [];
    }

    /**
     * @param  array<mixed>  $fields
     * @param  array<string, string>  $types
     * @param  array<string, true>  $conflicts
     */
    private static function collect(array $fields, array &$types, array &$conflicts): void
    {
        foreach ($fields as $field) {
            if (! is_array($field)) {
                continue;
            }

            $name = $field['name'] ?? '';
            $type = self::typeFor($field);
            if (is_string($name) && $name !== '' && $type !== null) {
                if (isset($types[$name]) && $types[$name] !== $type) {
                    $conflicts[$name] = true;
                }

                $types[$name] = $type;
            }

            foreach (['sub_fields', 'layouts'] as $children) {
                if (isset($field[$children]) && is_array($field[$children])) {
                    self::collect($field[$children], $types, $conflicts);
                }
            }
        }
    }

    /**
     * @param  array<mixed>  $field
     */
    public static function typeFor(array $field): ?string
    {
        $format = $field['return_format'] ?? 'value';

        $type = match ($field['type'] ?? '') {
            'text', 'textarea', 'wysiwyg', 'url', 'date_time_picker', 'select', 'radio', 'button_group' => 'string',
            'true_false' => 'bool',
            'number' => 'int|float|string',
            'image' => match ($format) {
                'id' => 'int',
                'array' => 'array<mixed>',
                default => 'string',
            },
            'link', 'repeater', 'gallery', 'flexible_content', 'group' => 'array<mixed>',
            default => null,
        };

        // ACF returns false or null for a field that was never saved, whatever its type.
        return $type === null ? null : $type.'|false|null';
    }
}
