<?php

declare(strict_types=1);

namespace Uams2020\Tests\Integration;

use Uams2020\Tests\Support\BlockIntegrationFields;
use Uams2020\Tests\Support\IntegrationTestCase;

require_once __DIR__.'/../Support/BlockIntegrationFields.php';
require_once dirname(__DIR__, 2).'/lib/blocks.php';

final class BlockTemplatesBehaviorTest extends IntegrationTestCase
{
    private function render(string $name, array $variables): string
    {
        BlockIntegrationFields::$values += ['post_tile_geo_region' => [], 'post_tiles_geo_region' => [], 'news_geo_region' => []];
        $post = $GLOBALS['post'] ?? null;
        $hadPost = array_key_exists('post', $GLOBALS);
        $level = ob_get_level();
        ob_start();
        try {
            (static function () use ($name, $variables): void {
                extract($variables, EXTR_SKIP);
                require dirname(__DIR__, 2).'/blocks/'.$name.'.php';
            })();

            return ob_get_contents();
        } finally {
            while (ob_get_level() > $level) {
                ob_end_clean();
            }

            wp_reset_postdata();
            if ($hadPost) {
                $GLOBALS['post'] = $post;
            } else {
                unset($GLOBALS['post']);
            }

            BlockIntegrationFields::$values = [];
        }
    }

    public function test_category_templates_select_only_the_latest_published_post_in_each_real_category(): void
    {
        $first = self::factory()->category->create_and_get(['name' => 'First category', 'slug' => 'first-choice']);
        $second = self::factory()->category->create_and_get(['name' => 'Second category', 'slug' => 'second-choice']);
        $posts = [];
        foreach ([['Old first', $first, 'publish', '2020-01-01 12:00:00'], ['Latest first', $first, 'publish', '2024-01-01 12:00:00'], ['Draft first', $first, 'draft', '2025-01-01 12:00:00'], ['Latest second', $second, 'publish', '2024-01-01 12:00:00']] as [$title, $category, $status, $date]) {
            $posts[$title] = self::factory()->post->create(['post_title' => $title, 'post_status' => $status, 'post_date' => $date, 'post_category' => [$category->term_id], 'post_excerpt' => 'Excerpt for '.$title]);
        }

        $attachment = self::factory()->attachment->create(['post_mime_type' => 'image/jpeg', 'guid' => 'https://example.org/thumbnail.jpg']);
        update_post_meta($attachment, '_wp_attached_file', 'thumbnail.jpg');
        update_post_meta($attachment, '_wp_attachment_image_alt', 'Native image "& alt');
        set_post_thumbnail($posts['Latest first'], $attachment);
        $common = ['id' => 'category-test', 'heading' => 'Heading <&', 'hide_heading' => true, 'background_color' => 'bg-white', 'geo' => 'include', 'geo_region' => [], 'is_preview' => false];
        $html = $this->render('post-category-tile', $common + ['category' => $first, 'post_button_text' => 'Read selected', 'cat_button_text' => 'Category archive']);
        $this->assertStringContainsString('Latest first', $html);
        $this->assertStringNotContainsString('Old first', $html);
        $this->assertStringNotContainsString('Draft first', $html);
        $this->assertStringNotContainsString('Latest second', $html);
        $this->assertStringContainsString('Excerpt for Latest first', $html);
        $this->assertStringContainsString(esc_url(get_permalink($posts['Latest first'])), $html);
        $this->assertStringContainsString(esc_attr('Native image "& alt'), $html);
        $this->assertStringContainsString(esc_html('Heading <&'), $html);
        $rows = array_map(static fn ($category): array => ['post_tiles_section_category' => $category, 'post_tiles_section_post_button_text' => '', 'post_tiles_section_category_button_text' => ''], [$first, $second]);
        $html = $this->render('post-category-tiles', $common + ['post_tiles_rows' => $rows]);
        foreach (['Latest first', 'Latest second', 'Read the Story', 'View First category Archive', 'View Second category Archive'] as $text) {
            $this->assertStringContainsString($text, $html);
        }

        $this->assertStringNotContainsString('Old first', $html);
        $this->assertStringNotContainsString('Draft first', $html);
        $empty = self::factory()->category->create_and_get(['name' => 'No posts']);
        $this->assertSame('', $this->render('post-category-tile', $common + ['category' => $empty]));
    }

    public function test_iframe_rendering_uses_real_host_and_sandbox_filters_and_escaping(): void
    {
        $hosts = static fn (): array => ['example.test'];
        $sandbox = static fn (): string => 'allow-forms "&';
        add_filter('uamswp_iframe_allowed_hosts', $hosts);
        add_filter('uamswp_iframe_sandbox', $sandbox);
        $fields = ['iframe_url' => 'https://example.test/embed?a=1&b=2', 'iframe_title' => 'Frame "& title', 'iframe_height' => -410, 'iframe_allow_fullscreen' => true];
        $vars = ['block' => ['id' => 'frame', 'className' => 'custom "&', 'align' => 'wide'], 'is_preview' => false];
        try {
            BlockIntegrationFields::$values = $fields;
            $html = $this->render('iframe', $vars);
            $this->assertStringContainsString('height:410px', $html);
            $this->assertStringContainsString('title="'.esc_attr($fields['iframe_title']).'"', $html);
            $this->assertStringContainsString('sandbox="'.esc_attr('allow-forms "&').'"', $html);
            $this->assertStringContainsString('src="'.esc_url($fields['iframe_url']).'"', $html);
            $this->assertStringContainsString('allowfullscreen', $html);
            remove_filter('uamswp_iframe_sandbox', $sandbox);
            $emptySandbox = static fn (): string => '';
            add_filter('uamswp_iframe_sandbox', $emptySandbox);
            try {
                BlockIntegrationFields::$values = $fields;
                $this->assertStringNotContainsString('sandbox=', $this->render('iframe', $vars));
            } finally {
                remove_filter('uamswp_iframe_sandbox', $emptySandbox);
            }

            $fields['iframe_url'] = 'https://unapproved.test/';
            BlockIntegrationFields::$values = $fields;
            $this->assertSame('', $this->render('iframe', $vars));
            BlockIntegrationFields::$values = $fields;
            $vars['is_preview'] = true;
            $vars['block']['data'] = ['iframe_url' => $fields['iframe_url']];
            $this->assertStringContainsString('not on the approved host list', $this->render('iframe', $vars));
        } finally {
            remove_filter('uamswp_iframe_allowed_hosts', $hosts);
            remove_filter('uamswp_iframe_sandbox', $sandbox);
        }
    }

    public function test_news_template_reaches_the_real_shortcode_parser_with_sanitized_attributes(): void
    {
        $received = null;
        $old = $GLOBALS['shortcode_tags']['uamswp_news'] ?? null;
        add_shortcode('uamswp_news', static function ($attrs) use (&$received): string {
            $received = $attrs;

            return '<news-rendered>';
        });
        $vars = ['id' => 7, 'title' => 'Title [bad] " <b>bold</b>', 'hide_title' => true, 'output' => 'grid', 'category' => 'cat[]"', 'count' => 99, 'offset' => -2, 'advancedCat' => 'advanced[]"', 'background_color' => 'bg-white', 'hide_img' => true, 'hide_author' => true, 'hide_date' => true, 'articleID' => -41, 'local' => true, 'link' => true, 'position' => 'right', 'geo' => 'include', 'geo_region' => []];
        try {
            $this->assertSame('<news-rendered>', $this->render('news', $vars));
            $this->assertSame('3', $received['count']);
            $this->assertSame('2', $received['offset']);
            $this->assertSame('41', $received['id']);
            $this->assertSame('Title bad  bold', $received['news_title']);
            $this->assertSame('cat', $received['category']);
            $this->assertSame('advanced', $received['advanced_cat']);
            $this->assertSame('grid', $received['output']);
        } finally {
            remove_shortcode('uamswp_news');
            if ($old !== null) {
                add_shortcode('uamswp_news', $old);
            }
        }
    }

    public function test_unused_side_image_width_lookup_still_runs_real_wordpress_filters(): void
    {
        $attachment = self::factory()->attachment->create(['post_mime_type' => 'image/jpeg', 'guid' => 'https://example.org/side-image.jpg']);
        update_post_meta($attachment, '_wp_attached_file', 'side-image.jpg');
        $filter = function ($image, $id, $size) use ($attachment) {
            $this->assertSame($attachment, $id);
            $this->assertSame('full', $size);
            echo '[real-image-source-filter]';

            return $image;
        };
        add_filter('wp_get_attachment_image_src', $filter, 10, 3);
        $vars = ['id' => 8, 'layout' => 'body-only', 'heading' => 'Side image', 'body' => 'Body', 'link_list' => [['unused']], 'list_more' => true, 'cta' => ['side_cta_text' => '', 'side_cta_url' => '', 'side_cta_description' => ''], 'image_group' => ['side_image_image' => $attachment, 'side_image_alt_text' => 'Alt', 'side_image_crop' => 'center', 'side_image_anchor' => 'center'], 'image_postion' => 'left', 'background_color' => 'bg-white', 'geo' => 'include', 'geo_region' => []];
        BlockIntegrationFields::$values = ['side_image_geo_region' => []];
        try {
            $html = $this->render('image-side-by-side', $vars);
            $this->assertStringStartsWith('[real-image-source-filter]', $html);
            $this->assertSame(1, substr_count($html, '[real-image-source-filter]'));
            $this->assertStringContainsString('side-by-side-image-side-8', $html);
        } finally {
            remove_filter('wp_get_attachment_image_src', $filter);
        }
    }
}
