<?php

declare(strict_types=1);

namespace Uams2020\Tests\Integration;

use Uams2020\Tests\Support\IntegrationTestCase;
use Uams2020\Tests\Support\OptionString;

foreach (['users', 'nav', 'media-credit', 'widgets', 'search'] as $library) {
    require_once dirname(__DIR__, 2).'/lib/'.$library.'.php';
}

require_once dirname(__DIR__, 2).'/lib/modules/class-wp-bootstrap-pagewalker.php';
require_once __DIR__.'/../Support/OptionString.php';

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

    public static function editorRoleShapes(): array
    {
        return [[false], [true]];
    }

    /** @dataProvider editorRoleShapes */
    public function test_editor_theme_screen_access_is_enforced_by_the_actual_admin_hook(bool $objectRoles): void
    {
        $user = self::factory()->user->create(['role' => 'editor']);
        $current = wp_set_current_user($user);
        $savedRoles = $current->roles;
        if ($objectRoles) {
            $current->roles = (object) ['primary' => 'editor'];
            $this->assertSame($current, wp_get_current_user());
        }

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
            $current->roles = $savedRoles;
            remove_filter('wp_die_handler', $handler);
            if ($headerPriority !== false) {
                add_action('admin_init', 'wp_admin_headers', $headerPriority);
            }

            $GLOBALS['pagenow'] = $old;
            $GLOBALS['current_screen'] = $oldScreen;
        }
    }

    /**
     * Core's menu.php declares a function, so it can be loaded once per process: this is the only
     * test that builds the admin menu. A snapshot taken on admin_menu at a priority before this
     * theme's callback records what core offered the editor, and the final menu shows what is left.
     */
    public function test_editor_loses_the_real_themes_and_widgets_entries_and_keeps_the_rest(): void
    {
        global $menu, $submenu, $_wp_submenu_nopriv, $_wp_menu_nopriv, $admin_page_hooks, $_registered_pages, $_parent_pages;

        // The theme grants editors edit_theme_options when it is activated; the test database has not run that hook.
        uamswp_editor_users();
        $editor = self::factory()->user->create(['role' => 'editor']);

        $slugs = static fn (array $items): array => array_values(array_map(static fn (array $item): string => (string) strtok((string) $item[2], '?'), $items));
        $offered = [];
        $snapshot = static function () use (&$offered, $slugs): void {
            global $submenu;
            $offered = $slugs($submenu['themes.php'] ?? []);
        };
        $priority = has_action('admin_menu', 'custom_admin_menu');
        $this->assertNotFalse($priority);
        add_action('admin_menu', $snapshot, $priority - 1);

        $saved = [$menu ?? null, $submenu ?? null, $_wp_submenu_nopriv ?? null, $_wp_menu_nopriv ?? null, $admin_page_hooks ?? null, $_registered_pages ?? null, $_parent_pages ?? null];
        wp_set_current_user($editor);
        add_theme_support('widgets');
        $menu = $submenu = $_wp_submenu_nopriv = $_wp_menu_nopriv = $admin_page_hooks = $_registered_pages = $_parent_pages = [];

        try {
            require_once ABSPATH.'wp-admin/includes/admin.php';
            wp_widgets_add_menu();
            require ABSPATH.'wp-admin/menu.php';
            $remaining = $slugs($submenu['themes.php'] ?? []);
        } finally {
            remove_action('admin_menu', $snapshot, $priority - 1);
            [$menu, $submenu, $_wp_submenu_nopriv, $_wp_menu_nopriv, $admin_page_hooks, $_registered_pages, $_parent_pages] = $saved;
            remove_theme_support('widgets');
        }

        $this->assertContains('themes.php', $offered);
        $this->assertContains('widgets.php', $offered);
        $this->assertContains('nav-menus.php', $offered);
        $this->assertContains('customize.php', $offered);
        $this->assertNotContains('themes.php', $remaining);
        $this->assertNotContains('widgets.php', $remaining);
        $this->assertSame(['themes.php', 'widgets.php'], array_values(array_diff($offered, $remaining)));
        $this->assertContains('nav-menus.php', $remaining);
        $this->assertContains('customize.php', $remaining);
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

    public function test_real_wordpress_filters_preserve_numeric_metadata_and_changing_menu_choices(): void
    {
        $page = self::factory()->post->create(['post_type' => 'page']);
        $reads = 0;
        $menu = static function ($available, $location) use (&$reads): bool {
            if ($location !== 'primary') {
                return $available;
            }

            return $reads++ === 0;
        };
        $metadata = (static fn ($value, $id, $key) => $id === $page && $key === 'page_hide_from_menu' ? [1] : $value);
        add_filter('has_nav_menu', $menu, 10, 2);
        add_filter('get_post_metadata', $metadata, 10, 3);
        try {
            $this->assertSame([true, false], [has_nav_menu('primary'), has_nav_menu('primary')]);
            $this->assertSame(2, $reads);
            $this->assertSame([1], get_post_meta($page, 'page_hide_from_menu'));
        } finally {
            remove_filter('has_nav_menu', $menu, 10);
            remove_filter('get_post_metadata', $metadata, 10);
        }
    }

    public function test_raw_cached_posts_preserve_extension_supplied_numeric_string_parents(): void
    {
        $parent = self::factory()->post->create(['post_type' => 'page', 'post_title' => 'Parent']);
        $child = self::factory()->post->create(['post_type' => 'page', 'post_parent' => $parent]);
        $raw = new \WP_Post((object) array_merge(get_post($child, ARRAY_A), ['post_parent' => (string) $parent, 'filter' => 'raw']));
        wp_cache_set($child, $raw, 'posts');
        try {
            $this->assertSame((string) $parent, get_post($child)->post_parent);
            $this->assertSame((string) $parent, get_post($raw)->post_parent);
            $page = get_post($parent);
            $walker = new \WP_Bootstrap_Pagewalker;
            $currentOutput = '';
            $walker->start_el($currentOutput, clone $page, 0, [], (string) $parent);
            $this->assertStringContainsString('current-page-item', $currentOutput);
            $parentOutput = '';
            $walker->start_el($parentOutput, clone $page, 0, [], $child);
            $this->assertStringContainsString('current-page-parent', $parentOutput);
        } finally {
            clean_post_cache($child);
        }
    }

    public function test_blog_name_option_filters_can_supply_arrays_without_core_coercion(): void
    {
        $value = ['Filtered name'];
        $filter = static fn (): array => $value;
        add_filter('pre_option_blogname', $filter);
        try {
            $this->assertSame($value, get_option('blogname'));
            $this->assertSame($value, get_bloginfo('name'));

        } finally {
            remove_filter('pre_option_blogname', $filter);

        }
    }

    public function test_raw_global_posts_preserve_extension_supplied_stringable_post_types(): void
    {
        $id = self::factory()->post->create(['post_type' => 'post']);
        $this->go_to(get_permalink($id));
        $saved = $GLOBALS['post'];
        $type = new OptionString('post');
        $raw = clone get_post($id);
        $raw->filter = 'raw';
        $raw->post_type = $type;
        $GLOBALS['post'] = $raw;
        try {
            $this->assertSame($type, get_post_type());
            $raw->post_type = 'post';
            $raw->post_title = new OptionString('Boundary title');
            $output = '';
            (new \WP_Bootstrap_Pagewalker)->start_el($output, $raw);
            $this->assertStringContainsString('title="Boundary title"', $output);
        } finally {
            $GLOBALS['post'] = $saved;
        }
    }

    public function test_filtered_boolean_block_names_reach_the_real_credit_filter(): void
    {
        $image = self::factory()->attachment->create(['post_mime_type' => 'image/jpeg']);
        update_post_meta($image, '_media_credit', 'Boundary photographer');
        $content = '<figure><img/></figure>';
        $block = ['blockName' => 'core/image', 'attrs' => ['id' => $image], 'innerBlocks' => [], 'innerHTML' => $content, 'innerContent' => [$content]];
        $filter = static function ($parsed) use ($image) {
            if (($parsed['attrs']['id'] ?? null) === $image) {
                $parsed['blockName'] = true;
            }

            return $parsed;
        };
        add_filter('render_block_data', $filter);
        try {
            $this->assertStringContainsString('Boundary photographer', render_block($block));
        } finally {
            remove_filter('render_block_data', $filter);
        }
    }

    public function test_unset_page_titles_can_change_through_actual_post_metadata_getters(): void
    {
        $id = self::factory()->post->create(['post_type' => 'page']);
        $page = get_post($id);
        unset($page->post_title);
        $reads = 0;
        $filter = static function ($value, $postId, $key) use ($id, &$reads) {
            if ($postId !== $id || $key !== 'post_title') {
                return $value;
            }

            return [$reads++ < 3 ? 'Initial title' : ''];
        };
        add_filter('get_post_metadata', $filter, 10, 3);
        try {
            $output = '';
            (new \WP_Bootstrap_Pagewalker)->start_el($output, $page);
            $this->assertStringNotContainsString(' title=', $output);
            $this->assertStringContainsString('class="nav-link"></a>', $output);
        } finally {
            remove_filter('get_post_metadata', $filter, 10);
        }
    }
}
