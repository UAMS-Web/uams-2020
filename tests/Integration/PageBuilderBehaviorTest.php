<?php

declare(strict_types=1);

namespace Uams2020\Tests\Integration;

use Uams2020\Tests\Support\IntegrationTestCase;

// Register before WP_UnitTestCase snapshots hooks, so every case sees the real callbacks.
require_once dirname(__DIR__, 2).'/lib/blocks.php';
require_once dirname(__DIR__, 2).'/lib/meta-box.php';

final class PageBuilderBehaviorTest extends IntegrationTestCase
{
    public function test_real_roles_control_the_menu_section_and_each_iframe_field(): void
    {
        foreach (['editor' => false, 'administrator' => true] as $role => $admin) {
            wp_set_current_user(self::factory()->user->create(['role' => $role]));
            $this->assertSame($admin, apply_filters('acf/settings/show_admin', ! $admin));
            $this->assertSame($admin, in_array('acf/uams-iframe', uamswp_section_allowed_blocks(), true));
            foreach (['url', 'title', 'height', 'fullscreen'] as $key) {
                $field = ['key' => 'field_iframe_'.$key.'_b', 'value' => 'kept'];
                $this->assertSame($admin ? $field : false, apply_filters('acf/prepare_field/key=field_iframe_'.$key.'_b', $field));
            }
        }

        $replace = static fn (): array => ['custom/block'];
        add_filter('uamswp_section_allowed_blocks', $replace);
        $this->assertSame(['custom/block'], uamswp_section_allowed_blocks());
        remove_filter('uamswp_section_allowed_blocks', $replace);
    }

    public function test_validation_uses_real_wordpress_filters_and_url_parsing(): void
    {
        $hook = 'acf/validate_value/key=field_iframe_url_b';
        $this->assertSame(10, has_action('acf/init', 'uams_register_blocks'));
        $this->assertSame(10, has_action('acf/init', 'uamswp_iframe_block_fields'));
        do_action('acf/init');
        $this->assertTrue(apply_filters($hook, true, 'HTTPS://EXAMPLE.TEST/path'));
        $this->assertTrue(apply_filters($hook, true, ''));
        $this->assertSame('earlier error', apply_filters($hook, 'earlier error', 'http://example.test'));
        $this->assertSame('Use an https:// address on an approved host.', apply_filters($hook, true, 'http://example.test'));
        $hosts = static fn (): array => ['EXAMPLE.TEST'];
        add_filter('uamswp_iframe_allowed_hosts', $hosts);
        $this->assertTrue(apply_filters($hook, true, 'https://example.test/path'));
        $this->assertSame('Use an https:// address on an approved host.', apply_filters($hook, true, 'https://sub.example.test'));
        remove_filter('uamswp_iframe_allowed_hosts', $hosts);
    }
}
