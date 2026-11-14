<?php

declare(strict_types=1);

use Uams2020\PhpStan\AcfFieldTypes;

/**
 * Writes a field-definition file into a fresh directory and returns the directory.
 *
 * @param  array<string, string>  $files  File name to PHP source returning an array.
 */
function acf_types_dir(array $files): string
{
    $dir = sys_get_temp_dir().'/acf-types-'.bin2hex(random_bytes(6));
    mkdir($dir);
    foreach ($files as $name => $source) {
        file_put_contents($dir.'/'.$name, '<?php return '.$source.';');
    }

    return $dir;
}

it('maps the type each field kind returns', function (array $field, ?string $expected): void {
    expect(AcfFieldTypes::typeFor($field))->toBe($expected);
})->with([
    'text' => [['type' => 'text'], 'string|false|null'],
    'textarea' => [['type' => 'textarea'], 'string|false|null'],
    'select' => [['type' => 'select'], 'string|false|null'],
    'true_false' => [['type' => 'true_false'], 'bool|false|null'],
    'number' => [['type' => 'number'], 'int|float|string|false|null'],
    'image value' => [['type' => 'image'], 'string|false|null'],
    'image id' => [['type' => 'image', 'return_format' => 'id'], 'int|false|null'],
    'image array' => [['type' => 'image', 'return_format' => 'array'], 'array<mixed>|false|null'],
    'link' => [['type' => 'link'], 'array<mixed>|false|null'],
    'repeater' => [['type' => 'repeater'], 'array<mixed>|false|null'],
    'taxonomy is not typed' => [['type' => 'taxonomy'], null],
    'unknown is not typed' => [['type' => 'accordion'], null],
    'missing type is not typed' => [[], null],
]);

it('reads fields from sub fields and layouts', function (): void {
    $dir = acf_types_dir(['a.php' => "[['name' => 'title', 'type' => 'text'], ['name' => 'rows', 'type' => 'repeater', 'sub_fields' => [['name' => 'flag', 'type' => 'true_false']]], ['name' => 'blocks', 'type' => 'flexible_content', 'layouts' => [['name' => 'one', 'sub_fields' => [['name' => 'caption', 'type' => 'textarea']]]]]]"]);

    expect(AcfFieldTypes::load($dir))->toBe([
        'title' => 'string|false|null',
        'rows' => 'array<mixed>|false|null',
        'flag' => 'bool|false|null',
        'blocks' => 'array<mixed>|false|null',
        'caption' => 'string|false|null',
    ]);
});

it('keeps a name defined twice with the same type and drops one defined with different types', function (): void {
    $dir = acf_types_dir([
        'a.php' => "[['name' => 'same', 'type' => 'text'], ['name' => 'clash', 'type' => 'text']]",
        'b.php' => "[['name' => 'same', 'type' => 'select'], ['name' => 'clash', 'type' => 'true_false']]",
    ]);

    expect(AcfFieldTypes::load($dir))->toBe(['same' => 'string|false|null']);
});

it('skips malformed entries and files that do not return an array', function (): void {
    $dir = acf_types_dir([
        'a.php' => "['not-a-field', ['name' => '', 'type' => 'text'], ['type' => 'text'], ['name' => 'ok', 'type' => 'text']]",
        'b.php' => "'nope'",
    ]);

    expect(AcfFieldTypes::load($dir))->toBe(['ok' => 'string|false|null']);
});

it('returns nothing for a directory with no definitions', function (): void {
    expect(AcfFieldTypes::load(acf_types_dir([])))->toBe([]);
});

it('reads the real field definitions without error', function (): void {
    $types = AcfFieldTypes::load(dirname(__DIR__, 2).'/acf_fields');

    expect($types)->not->toBeEmpty()
        ->and(array_unique(array_values($types)))->each->toBeString();
});
