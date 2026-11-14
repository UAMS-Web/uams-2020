<?php

declare(strict_types=1);
/*
 *
 * User functions
 *
 */
// Set these capabilities once when the theme is activated, not on every request.
// (Removing them when the theme is switched away is deferred: see #574, because the
// Gravity Forms access is intentional site policy and may be meant to persist.)
add_action('after_switch_theme', 'uamswp_editor_users');
function uamswp_editor_users(): void
{
    $role = get_role('editor');
    $role->add_cap('edit_theme_options');
    // ------------------------------------------------------//
    // -----------------Give Editors Gravity Forms Access - TM
    // ------------------------------------------------------//
    $role->add_cap('gravityforms_edit_forms');
    $role->add_cap('gravityforms_delete_forms');
    $role->add_cap('gravityforms_create_form');
    $role->add_cap('gravityforms_view_entries');
    $role->add_cap('gravityforms_edit_entries');
    $role->add_cap('gravityforms_delete_entries');
    $role->add_cap('gravityforms_view_settings');
    $role->add_cap('gravityforms_edit_settings');
    $role->add_cap('gravityforms_export_entries');
    $role->add_cap('gravityforms_view_entry_notes');
    $role->add_cap('gravityforms_edit_entry_notes');
}

/*
 * Whether the current user is an editor who is not also an administrator.
 *
 * Both the Appearance menu hiding and the screen guard below use this test, so the
 * entries that are hidden are exactly the screens that are refused (see #783).
 */
function uamswp_is_restricted_editor(): bool
{
    // Administrators (and super admins) always pass.
    if (current_user_can('manage_options')) {
        return false;
    }

    // Only users who actually hold the editor role are restricted.
    $user = wp_get_current_user();

    return $user instanceof WP_User && in_array('editor', (array) $user->roles, true);
}

/*
 * Hide the Themes and Widgets entries from the Appearance menu for editors.
 *
 * The entries are removed by slug, so a WordPress release that renumbers the
 * submenu cannot hide the wrong item. Menus and the Customizer stay available.
 */
add_action('admin_menu', 'custom_admin_menu');
function custom_admin_menu(): void
{
    if (! uamswp_is_restricted_editor()) {
        return;
    }

    remove_submenu_page('themes.php', 'themes.php');
    remove_submenu_page('themes.php', 'widgets.php');
}

/*
 * Enforce the editor restriction that custom_admin_menu() only hides.
 *
 * Editors are granted edit_theme_options so they can reach Appearance > Menus
 * and the Customizer, but hiding the Themes/Widgets menu entries is cosmetic
 * only and does not stop a direct URL to themes.php/widgets.php. This backs the
 * hidden menus with a real access-control check for those two screens, while
 * leaving Menus (nav-menus.php) and the Customizer intact.
 *
 */
add_action('admin_init', 'uamswp_restrict_editor_theme_screens');
function uamswp_restrict_editor_theme_screens(): void
{
    if (! uamswp_is_restricted_editor()) {
        return;
    }

    // Deny direct access to exactly the screens custom_admin_menu() hides.
    global $pagenow;
    if (in_array($pagenow, ['themes.php', 'widgets.php'], true)) {
        wp_die(
            __('Sorry, you are not allowed to access this page.'),
            '',
            ['response' => 403]
        );
    }
}

/**
 * Close the Customizer Widgets panel for editors (see #785).
 *
 * Editors hold edit_theme_options, which gates the Widgets panel, its sidebar
 * sections and its widget settings. Removing the widgets component keeps all
 * three from registering, so a forged Customizer save has no setting to write to.
 * Menus (nav_menus) and every other component stay available.
 *
 * @param  array<int, string>  $components
 * @return array<int, string>
 */
add_filter('customize_loaded_components', 'uamswp_close_customizer_widgets_for_editors');
function uamswp_close_customizer_widgets_for_editors(array $components): array
{
    if (! uamswp_is_restricted_editor()) {
        return $components;
    }

    return array_values(array_diff($components, ['widgets']));
}

/*
 * Capture user login and add it as timestamp in user meta data
 *
 */

/**
 * @param  mixed  $user
 * @param  mixed  $user_login
 */
function uamswp_user_last_login($user_login, $user): void
{
    update_user_meta($user->ID, 'last_login', time());
}

add_action('wp_login', 'uamswp_user_last_login', 10, 2);

/*
 * Display last login time
 *
 */

/**
 * @return mixed
 */
function uamswp_lastlogin()
{
    // Do not disclose author login activity to public visitors.
    if (! is_user_logged_in()) {
        return '';
    }

    $last_login = get_the_author_meta('last_login');

    return human_time_diff($last_login);
}

/*
 * Add Shortcode lastlogin
 *
 */

add_shortcode('lastlogin', 'uamswp_lastlogin');

/*
 * Add user column
 *
 */

/**
 * @param  mixed  $column
 * @return mixed
 */
function uamswp_modify_user_table($column)
{
    $column['last_login'] = 'Last Login';

    return $column;
}

add_filter('manage_users_columns', 'uamswp_modify_user_table');

/**
 * @param  mixed  $column_name
 * @param  mixed  $user_id
 * @param  mixed  $val
 * @return mixed
 */
function uamswp_modify_user_table_row($val, $column_name, $user_id)
{
    switch ($column_name) {
        case 'last_login' :
            $the_login_date = __('Never', 'uamswp');
            $last_login = get_the_author_meta('last_login', $user_id);
            if ($last_login) {
                return human_time_diff($last_login);
            }

            return $the_login_date;
        default:
    }

    return $val;
}

add_filter('manage_users_custom_column', 'uamswp_modify_user_table_row', 10, 3);
