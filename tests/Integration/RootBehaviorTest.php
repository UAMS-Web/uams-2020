<?php

declare(strict_types=1);

namespace Uams2020\Tests\Integration;

use Closure;
use ReflectionFunction;
use Uams2020\Tests\Support\IntegrationTestCase;
use WP_Block_Editor_Context;
use WP_Block_Type_Registry;
use WP_Theme;

final class RootBehaviorTest extends IntegrationTestCase
{
    public function set_up(): void
    {
        parent::set_up();
        require_once __DIR__.'/../Fixtures/integration-boundaries.php';
        require_once dirname(__DIR__, 2).'/lib/blocks.php';
        require_once dirname(__DIR__, 2).'/search.php';
        $GLOBALS['uams2020_image_calls'] = [];
        $GLOBALS['uams2020_loop_ids'] = [];
    }

    public function tear_down(): void
    {
        unset($GLOBALS['uams2020_image_calls'], $GLOBALS['uams2020_loop_ids']);
        parent::tear_down();
    }

    public function test_theme_hooks_have_the_real_priorities_and_argument_counts(): void
    {
        $this->assertSame(15, has_action('genesis_setup', 'uamswp_childtheme_setup'));
        $this->assertSame(5, has_action('genesis_before_header', 'uamswp_skip_links'));
        $this->assertSame(10, has_filter('dynamic_sidebar_params', 'footer_widgets'));
        $this->assertSame(10, has_filter('retrieve_password_message', 'my_retrieve_password_message'));
        // ReflectionFunction returns the platform's separators, so both paths are normalized before the comparison.
        $functionsFile = wp_normalize_path(dirname(__DIR__, 2).'/functions.php');
        foreach ([['cron_schedules', 10, 1], ['process_image_sizer_queue', 10, 1], ['admin_init', 10, 1], ['admin_notices', 10, 1], ['switch_theme', 10, 3], ['init', PHP_INT_MAX, 1], ['allowed_block_types_all', 100, 2], ['allowed_block_types_all', 110, 2], ['srm_map_meta_caps', 10, 1]] as [$hook, $priority, $accepted]) {
            $entries = array_filter($GLOBALS['wp_filter'][$hook]->callbacks[$priority], static fn (array $entry): bool => $entry['function'] instanceof Closure
                && wp_normalize_path((new ReflectionFunction($entry['function']))->getFileName()) === $functionsFile);
            $this->assertCount(1, $entries, $hook);
            $this->assertSame($accepted, array_values($entries)[0]['accepted_args'], $hook);
        }
    }

    public function test_queue_batches_persist_transients_and_remove_the_finished_schedule(): void
    {
        $queue = [];
        for ($id = 1; $id <= 21; $id++) {
            $queue[$id * 3] = ['id' => $id, 'params' => ['width' => 100, 'height' => 50, 'hcrop' => 'left', 'vcrop' => 'top'], 'transient_key' => 'uams-test-image-'.$id];
        }

        update_option('image_sizer_queue', $queue);
        wp_schedule_event(time() + 60, 'ten_seconds', 'process_image_sizer_queue');
        do_action('process_image_sizer_queue');
        $this->assertCount(20, $GLOBALS['uams2020_image_calls']);
        $this->assertSame([[0 => 1, 1 => [100, 50], 2 => ['left', 'top']]][0], $GLOBALS['uams2020_image_calls'][0]);
        $this->assertSame([array_values($queue)[20]], get_option('image_sizer_queue'));
        $this->assertSame('https://example.test/resized/1', get_transient('uams-test-image-1'));
        $this->assertGreaterThanOrEqual(time() + 431990, get_option('_transient_timeout_uams-test-image-1'));
        $this->assertLessThanOrEqual(time() + 432000, get_option('_transient_timeout_uams-test-image-1'));
        $this->assertNotFalse(wp_next_scheduled('process_image_sizer_queue'));
        do_action('process_image_sizer_queue');
        $this->assertCount(21, $GLOBALS['uams2020_image_calls']);
        $this->assertFalse(get_option('image_sizer_queue'));
        $this->assertFalse(wp_next_scheduled('process_image_sizer_queue'));
    }

    public function test_switching_away_clears_queue_work_but_other_themes_do_not(): void
    {
        update_option('image_sizer_queue', [['id' => 1]]);
        wp_schedule_event(time() + 60, 'ten_seconds', 'process_image_sizer_queue');
        do_action('switch_theme', 'Other', new WP_Theme('other', '/tmp'), new WP_Theme('not-uams', '/tmp'));
        $this->assertSame([['id' => 1]], get_option('image_sizer_queue'));
        do_action('switch_theme', 'Other', new WP_Theme('other', '/tmp'), new WP_Theme('uams-2020', '/tmp'));
        $this->assertFalse(get_option('image_sizer_queue'));
        $this->assertFalse(wp_next_scheduled('process_image_sizer_queue'));
    }

    public function test_marketing_and_global_block_filters_respect_editor_and_admin_roles(): void
    {
        register_block_type('acf/uams-iframe');
        $post = self::factory()->post->create_and_get(['post_type' => 'page']);
        update_post_meta($post->ID, '_wp_page_template', 'templates/marketing.php');
        $context = new WP_Block_Editor_Context(['post' => $post]);
        foreach (['editor' => false, 'administrator' => true] as $role => $iframe) {
            wp_set_current_user(self::factory()->user->create(['role' => $role]));
            $allowed = apply_filters('allowed_block_types_all', true, $context);
            $this->assertContains('acf/uams-content', $allowed);
            $this->assertContains('core/paragraph', $allowed);
            $this->assertNotContains('core/spacer', $allowed);
            $this->assertSame($iframe, in_array('acf/uams-iframe', $allowed, true));
        }

        unregister_block_type('acf/uams-iframe');
    }

    public function test_custom_loop_uses_real_queries_and_restores_the_main_query(): void
    {
        $first = self::factory()->post->create(['post_title' => 'Needle first', 'post_date' => '2025-01-01 10:00:00']);
        self::factory()->post->create(['post_title' => 'Needle second', 'post_date' => '2025-02-01 10:00:00']);
        self::factory()->post->create(['post_title' => 'Other']);
        $this->go_to('/?s=Needle');
        $mainQuery = $GLOBALS['wp_query'];
        $GLOBALS['more'] = 9;
        $filter = static fn (array $args): array => array_merge($args, ['posts_per_page' => 1]);
        add_filter('genesis_custom_loop_args', $filter);
        \uamswp_custom_loop(['s' => 'Needle', 'post_type' => 'post', 'orderby' => 'date', 'order' => 'ASC']);
        remove_filter('genesis_custom_loop_args', $filter);
        $this->assertSame([$first], $GLOBALS['uams2020_loop_ids']);
        $this->assertSame($mainQuery, $GLOBALS['wp_query']);
        $this->assertSame(0, $GLOBALS['more']);
        \uamswp_custom_loop_base(['s' => 'Needle', 'post_type' => 'post', 'posts_per_page' => -1]);
        $this->assertNotSame($mainQuery, $GLOBALS['wp_query']);
        $this->assertSame(2, $GLOBALS['wp_query']->found_posts);
        wp_reset_query();
    }

    public function test_post_format_boundary_selects_external_links_only_for_link_posts(): void
    {
        $hadSupport = post_type_supports('post', 'post-formats');
        add_post_type_support('post', 'post-formats');
        $id = self::factory()->post->create();
        $canonical = get_permalink($id);
        update_post_meta($id, 'post_custom_link', 'https://example.test/external');
        try {
            set_post_format($id, 'link');
            $this->assertSame('link', get_post_format($id));
            $this->assertSame('https://example.test/external', \uams_get_permalink($id));
            set_post_format($id, 'aside');
            $this->assertSame('aside', get_post_format($id));
            $this->assertSame($canonical, \uams_get_permalink($id));
            set_post_format($id, false);
            $this->assertFalse(get_post_format($id));
            $this->assertSame($canonical, \uams_get_permalink($id));
        } finally {
            if (! $hadSupport) {
                remove_post_type_support('post', 'post-formats');
            }
        }
    }

    public function test_registry_rejects_the_quoted_name_and_keeps_the_valid_next_block(): void
    {
        $registry = WP_Block_Type_Registry::get_instance();
        $this->setExpectedIncorrectUsage('WP_Block_Type_Registry::register');
        $this->assertFalse(register_block_type('core/query-pagination-next"'));
        $this->assertArrayNotHasKey('core/query-pagination-next"', $registry->get_all_registered());
        $valid = 'core/query-pagination-next';
        $hadValid = $registry->is_registered($valid);
        if (! $hadValid) {
            register_block_type($valid);
        }

        try {
            $allowed = apply_filters('allowed_block_types_all', true, new WP_Block_Editor_Context);
            $this->assertContains($valid, $allowed);
        } finally {
            if (! $hadValid) {
                unregister_block_type($valid);
            }
        }
    }
}
