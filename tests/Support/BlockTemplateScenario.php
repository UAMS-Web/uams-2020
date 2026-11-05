<?php

declare(strict_types=1);

namespace Uams2020\Tests\Support;

use PHPUnit\Framework\Assert;

use function Brain\Monkey\Functions\when;
use function Patchwork\redefine;
use function Patchwork\relay;

/** Deterministic external boundaries for fixed block-rendering fixtures. */
final class BlockTemplateScenario
{
    public static function render(array $case): array
    {
        $calls = [];
        if ($case['name'] === 'post-category-tile' && isset($case['variables']['category'])) {
            $case['variables']['category'] = (object) $case['variables']['category'];
        }
        foreach ($case['variables']['post_tiles_rows'] ?? [] as $i => $row) {
            $case['variables']['post_tiles_rows'][$i]['post_tiles_section_category'] = (object) $row['post_tiles_section_category'];
        }
        $fields = $case['fields'];
        foreach (['post_tile_category'] as $key) {
            if (isset($fields[$key])) {
                $fields[$key] = (object) $fields[$key];
            }
        }
        foreach ($fields['post_tiles_section'] ?? [] as $i => $row) {
            $fields['post_tiles_section'][$i]['post_tiles_section_category'] = (object) $row['post_tiles_section_category'];
        }
        when('get_field')->alias(static function ($key, ...$args) use ($fields, &$calls) {
            Assert::assertArrayHasKey($key, $fields);
            $calls[] = ['get_field', $key, $args];

            return $fields[$key];
        });
        when('uamswp_block_placeholder')->alias(static function (...$args) use ($case, &$calls): bool {
            $calls[] = ['placeholder', $args];

            return $case['placeholder'];
        });
        when('function_exists')->alias(static fn ($name) => $name === 'bis_get_attachment_image' ? $case['resizer'] : relay());
        when('is_in_region')->alias(static function ($regions) use ($case, &$calls): bool {
            $calls[] = ['is_in_region', $regions];

            return $case['in_region'];
        });
        when('is_not_in_region')->alias(static function ($regions) use ($case, &$calls): bool {
            $calls[] = ['is_not_in_region', $regions];

            return $case['out_region'];
        });
        when('is_admin')->justReturn($case['admin']);
        foreach (['esc_attr' => 'ATTR', 'esc_html' => 'HTML', 'esc_url' => 'URL', 'wp_kses_post' => 'POST', 'sanitize_title_with_dashes' => 'SLUG', 'sanitize_html_class' => 'CLASS', 'sanitize_text_field' => 'TEXT'] as $function => $marker) {
            when($function)->alias(static fn ($value): string => $marker.'('.(string) $value.')');
        }
        when('__')->returnArg();
        when('esc_html__')->alias(static fn ($value): string => 'HTML('.$value.')');
        when('absint')->alias(static fn ($value): int => abs((int) $value));
        when('wp_json_encode')->alias(static fn ($value): string => json_encode($value, JSON_THROW_ON_ERROR));
        when('wp_unique_id')->justReturn('unique-19');
        when('uamswp_section_allowed_blocks')->justReturn(['core/paragraph', 'acf/content']);
        when('uamswp_iframe_url_is_allowed')->alias(static function ($url) use ($case, &$calls): bool {
            $calls[] = ['iframe_allowed', $url];

            return $case['iframe_allowed'];
        });
        redefine('apply_filters', static function ($hook, $value, ...$args) use ($case, &$calls) {
            if ($hook !== 'uamswp_iframe_sandbox') {
                return relay();
            }
            $calls[] = ['apply_filters', $hook, $value, $args];

            return $case['sandbox'];
        });
        when('image_sizer')->alias(static fn (...$args): string => 'sized:'.json_encode($args, JSON_THROW_ON_ERROR));
        when('gallery_image_dimension')->alias(static function (...$args) use (&$calls): float {
            $calls[] = ['gallery_image_dimension', $args];

            return (['xxl' => 2000, 'xl' => 1500, 'lg' => 1200, 'md' => 992, 'sm' => 768, 'xs' => 576][$args[0]] / $args[1]) / (isset($args[3]) && is_numeric($args[3]) && (float) $args[3] > 0 ? (float) $args[3] : 1);
        });
        when('wp_get_attachment_url')->alias(static fn ($id): string => 'attachment:'.$id);
        when('wp_get_attachment_image_url')->alias(static fn ($id, $size): string => 'image:'.$id.':'.$size);
        when('wp_get_attachment_image_src')->alias(static fn ($id, $size): array => ['image:'.$id.':'.$size, 1200, 675]);
        when('get_post_meta')->alias(static fn ($id, $key, $single): string => 'meta:'.$id.':'.$key.':'.(int) $single);
        when('format_phone_dash')->alias(static fn ($value): string => 'dash:'.$value);
        when('format_phone_us')->alias(static fn ($value): string => 'us:'.$value);
        when('do_shortcode')->alias(static fn ($value): string => 'SHORTCODE('.$value.')');
        when('date')->alias(static fn ($format, $timestamp = null): string => gmdate($format, $timestamp ?? 1710504000));
        when('strtotime')->alias(static fn ($value, $base = null): int|false => $value === '' ? false : (new \DateTimeImmutable('@'.($base ?? 1710504000)))->setTimezone(new \DateTimeZone('UTC'))->modify($value)->getTimestamp());
        if (! class_exists('WP_Query')) {
            class_alias(QueryDouble::class, 'WP_Query');
        }
        QueryDouble::$nextRows = $case['rows'];
        QueryDouble::$history = [];
        when('get_post_thumbnail_id')->alias(static function ($id) use ($case, &$calls) {
            $calls[] = ['get_post_thumbnail_id', $id];

            return $case['thumbnail'];
        });
        when('the_title')->alias(static function (): void {
            echo 'Post <title> &';
        });
        when('the_permalink')->alias(static function (): void {
            echo 'https://example.test/post';
        });
        when('get_category_link')->alias(static fn ($id): string => 'category:'.$id);
        when('has_excerpt')->justReturn($case['excerpt']);
        when('get_the_excerpt')->justReturn(str_repeat('Excerpt & long words ', 20));
        when('get_the_content')->justReturn('<p>'.str_repeat('Body text https://example.test/a?q=b next ', 15).'</p>');
        when('wp_strip_all_tags')->alias(static fn ($value, $removeBreaks = false): string => 'STRIPPED('.strip_tags($value).')'.(int) $removeBreaks);
        when('wp_trim_words')->alias(static fn ($value, $number = 55): string => implode(' ', array_slice(explode(' ', $value), 0, $number)));
        $before = $GLOBALS['post'] ?? null;
        $hadPost = array_key_exists('post', $GLOBALS);
        $level = ob_get_level();
        ob_start();
        try {
            self::include($case['name'], $case['variables']);
            $html = ob_get_contents();
        } finally {
            while (ob_get_level() > $level) {
                ob_end_clean();
            }
            if ($hadPost) {
                $GLOBALS['post'] = $before;
            } else {
                unset($GLOBALS['post']);
            }
            QueryDouble::$nextRows = [];
        }

        $queries = QueryDouble::$history;
        QueryDouble::$history = [];

        return ['html' => $html, 'calls' => $calls, 'queries' => $queries];
    }

    private static function include(string $name, array $variables): void
    {
        extract($variables, EXTR_SKIP);
        require __DIR__.'/../../blocks/'.$name.'.php';
    }
}
