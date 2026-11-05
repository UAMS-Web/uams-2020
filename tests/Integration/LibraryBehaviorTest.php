<?php

declare(strict_types=1);

namespace Uams2020\Tests\Integration;

use Uams2020\Tests\Support\IntegrationTestCase;

foreach (['users', 'nav', 'media-credit', 'widgets', 'search'] as $library) {
    require_once dirname(__DIR__, 2).'/lib/'.$library.'.php';
}

require_once dirname(__DIR__, 2).'/lib/modules/class-wp-bootstrap-pagewalker.php';

final class LibraryBehaviorTest extends IntegrationTestCase
{
    public function test_editor_capabilities_and_login_metadata_use_real_wordpress_roles_and_storage(): void
    {
        $role = get_role('editor');
        $saved = $role->capabilities;
        $oldAuthor = $GLOBALS['authordata'] ?? null;
        try {
            do_action('after_switch_theme');
            foreach (['edit_theme_options', 'gravityforms_edit_forms', 'gravityforms_export_entries'] as $capability) {
                $this->assertTrue($role->has_cap($capability));
            }

            $user = self::factory()->user->create_and_get(['role' => 'editor']);
            $before = time();
            do_action('wp_login', $user->user_login, $user);
            $this->assertGreaterThanOrEqual($before, (int) get_user_meta($user->ID, 'last_login', true));
            $this->assertLessThanOrEqual(time(), (int) get_user_meta($user->ID, 'last_login', true));
            wp_set_current_user(0);
            $this->assertSame('', do_shortcode('[lastlogin]'));
            wp_set_current_user($user->ID);
            $GLOBALS['authordata'] = $user;
            $this->assertNotSame('', do_shortcode('[lastlogin]'));
        } finally {
            $GLOBALS['authordata'] = $oldAuthor;
            foreach (array_diff_key($role->capabilities, $saved) as $capability => $grant) {
                $role->remove_cap($capability);
            }

            foreach ($saved as $capability => $grant) {
                $role->add_cap($capability, $grant);
            }
        }
    }

    public function test_editor_theme_screen_access_is_enforced_by_the_actual_admin_hook(): void
    {
        $user = self::factory()->user->create(['role' => 'editor']);
        wp_set_current_user($user);
        $old = $GLOBALS['pagenow'] ?? null;
        $oldScreen = $GLOBALS['current_screen'] ?? null;
        set_current_screen('widgets');
        $GLOBALS['pagenow'] = 'widgets.php';
        $handler = static fn (): \Closure => static function ($message, $title, $args): void {
            if (($args['response'] ?? null) !== 403) {
                throw new \LogicException('Unexpected denial status.');
            }

            throw new \RuntimeException('Expected editor denial');
        };
        add_filter('wp_die_handler', $handler);
        $headerPriority = has_action('admin_init', 'wp_admin_headers');
        if ($headerPriority !== false) {
            remove_action('admin_init', 'wp_admin_headers', $headerPriority);
        }

        try {
            $this->expectException(\RuntimeException::class);
            $this->expectExceptionMessage('Expected editor denial');
            do_action('admin_init');
        } finally {
            remove_filter('wp_die_handler', $handler);
            if ($headerPriority !== false) {
                add_action('admin_init', 'wp_admin_headers', $headerPriority);
            }

            $GLOBALS['pagenow'] = $old;
            $GLOBALS['current_screen'] = $oldScreen;
        }
    }

    public function test_subsection_navigation_uses_real_page_ancestry_and_metadata(): void
    {
        $root = self::factory()->post->create(['post_type' => 'page', 'post_title' => 'Root']);
        $near = self::factory()->post->create(['post_type' => 'page', 'post_parent' => $root, 'post_title' => 'Near']);
        $leaf = self::factory()->post->create(['post_type' => 'page', 'post_parent' => $near, 'post_title' => 'Leaf']);
        update_post_meta($root, 'page_subsection', '1');
        update_post_meta($near, 'page_subsection', '1');
        $this->go_to(get_permalink($leaf));
        $GLOBALS['post'] = get_post($leaf);
        $this->assertSame($near, uamswp_nav_subsection());
        update_post_meta($leaf, 'page_subsection', '1');
        $this->assertSame($leaf, uamswp_nav_subsection());
        $this->go_to('/?s=Leaf');
        $this->assertFalse(uamswp_nav_subsection());
        $markup = wp_list_pages(['echo' => false, 'title_li' => '', 'walker' => new \WP_Bootstrap_Pagewalker]);
        $this->assertStringContainsString('page-item-'.$near, $markup);
        $this->assertStringContainsString('aria-labelledby="menu-item-dropdown-'.$near.'"', $markup);
        $this->assertStringContainsString('dropdown-item', $markup);
    }

    public function test_subsection_reads_can_change_between_calls_through_real_metadata_filters(): void
    {
        $page = self::factory()->post->create(['post_type' => 'page']);
        $this->go_to(get_permalink($page));
        $GLOBALS['post'] = get_post($page);
        $reads = 0;
        $filter = static function ($value, $id, $key) use ($page, &$reads) {
            if ($id === $page && $key === 'page_subsection') {
                return [$reads++ > 0];
            }

            return $value;
        };
        add_filter('get_post_metadata', $filter, 10, 3);
        try {
            $this->assertFalse(uamswp_nav_subsection());
            $this->assertSame($page, uamswp_nav_subsection());
        } finally {
            remove_filter('get_post_metadata', $filter, 10);
        }
    }

    public function test_media_credit_filters_round_trip_metadata_and_render_real_image_blocks(): void
    {
        $image = self::factory()->attachment->create(['post_mime_type' => 'image/jpeg']);
        $post = ['ID' => $image];
        $this->assertSame($post, apply_filters('attachment_fields_to_save', $post, ['media_credit' => 'Photographer & Co']));
        $this->assertSame('Photographer & Co', get_post_meta($image, '_media_credit', true));
        $content = '<figure class="wp-block-image"><img src="https://example.test/photo.jpg" class="wp-image-'.$image.'"/></figure>';
        $block = ['blockName' => 'core/image', 'attrs' => ['id' => $image], 'innerBlocks' => [], 'innerHTML' => $content, 'innerContent' => [$content]];
        $rendered = render_block($block);
        $this->assertStringContainsString('photo-credit', $rendered);
        $this->assertStringContainsString('Photographer &amp; Co', $rendered);
        apply_filters('attachment_fields_to_save', $post, ['media_credit' => '']);
        $this->assertSame('', get_post_meta($image, '_media_credit', true));
    }

    public function test_widgets_register_with_real_parent_ids_and_form_field_names(): void
    {
        global $wp_widget_factory;
        $saved = $wp_widget_factory->widgets;
        $registrations = [];
        foreach (['wp_registered_widgets', 'wp_registered_widget_controls', 'wp_registered_widget_updates'] as $global) {
            $registrations[$global] = $GLOBALS[$global];
        }

        try {
            do_action('widgets_init');
            foreach (['UAMSWP_Call_Out_Widget' => 'uamswp_callout_widget', 'UAMSWP_CTA_Widget' => 'uamswp_cta_widget'] as $class => $id) {
                $this->assertArrayHasKey($class, $wp_widget_factory->widgets);
                $widget = $wp_widget_factory->widgets[$class];
                $widget->_set(9);
                $this->assertSame($id, $widget->id_base);
                $this->assertSame('widget-'.$id.'-9-title', $widget->get_field_id('title'));
                $this->assertSame('widget-'.$id.'[9][title]', $widget->get_field_name('title'));
                $this->assertSame(['title' => 'Saved'], $widget->update(['title' => '<b>Saved</b>'], []));
            }
        } finally {
            $wp_widget_factory->widgets = $saved;
            foreach ($registrations as $global => $value) {
                $GLOBALS[$global] = $value;
            }
        }

        $this->assertTrue($saved === $wp_widget_factory->widgets, 'Widget factory was not restored.');
        foreach ($registrations as $global => $value) {
            $this->assertTrue($value === $GLOBALS[$global], 'Widget registration was not restored: '.$global);
        }
    }

    public function test_search_form_filter_uses_the_current_real_search_query(): void
    {
        $this->go_to('/?s=Library+Needle');
        $markup = get_search_form(['echo' => false]);
        $this->assertStringContainsString('id="uams-search-bar" value="Library Needle"', $markup);
        $this->assertStringContainsString('name="search-scope"', $markup);
    }
}
