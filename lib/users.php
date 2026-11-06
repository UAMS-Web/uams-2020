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

add_action('admin_menu', 'custom_admin_menu');
function custom_admin_menu(): void
{
    new WP_User(get_current_user_id());
    if (isset($role) && $role == 'editor') {
        remove_submenu_page('themes.php', 'themes.php');
        remove_submenu_page('themes.php', 'widgets.php');
        global $submenu;
        unset($submenu['themes.php'][6]);
        unset($submenu['themes.php'][15]);
    }
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
    // Administrators (and super admins) always pass.
    if (current_user_can('manage_options')) {
        return;
    }

    // Only enforce against users who actually hold the editor role.
    $user = wp_get_current_user();
    if (! ($user instanceof WP_User) || ! in_array('editor', (array) $user->roles, true)) {
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

/*
 * Capture user login and add it as timestamp in user meta data
 *
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

function uamswp_modify_user_table($column)
{
    $column['last_login'] = 'Last Login';

    return $column;
}

add_filter('manage_users_columns', 'uamswp_modify_user_table');

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
