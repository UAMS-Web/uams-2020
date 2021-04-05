<?php
/*
 *
 * User functions
 * 
 */
add_action( 'init', 'uamswp_editor_users', 0 );
function uamswp_editor_users() {
    $role = get_role('editor');
    $role->add_cap('edit_theme_options');
    //------------------------------------------------------//
	//-----------------Give Editors Gravity Forms Access - TM
	//------------------------------------------------------//
    $role->add_cap( 'gravityforms_edit_forms' );
	$role->add_cap( 'gravityforms_delete_forms' );
	$role->add_cap( 'gravityforms_create_form' );
	$role->add_cap( 'gravityforms_view_entries' );
	$role->add_cap( 'gravityforms_edit_entries' );
	$role->add_cap( 'gravityforms_delete_entries' );
	$role->add_cap( 'gravityforms_view_settings' );
	$role->add_cap( 'gravityforms_edit_settings' );
	$role->add_cap( 'gravityforms_export_entries' );
	$role->add_cap( 'gravityforms_view_entry_notes' );
	$role->add_cap( 'gravityforms_edit_entry_notes' );
}
add_action('admin_menu', 'custom_admin_menu');
function custom_admin_menu() {
    $user = new WP_User(get_current_user_id());
    if (!empty( $user->roles) && is_array($user->roles)) {
        foreach ($user->roles as $role)
            $role = $role;
    }

    if(isset($role) && $role == "editor") {
       remove_submenu_page( 'themes.php', 'themes.php' );
       remove_submenu_page( 'themes.php', 'widgets.php' );
       global $submenu;
        unset($submenu['themes.php'][6]);
        unset($submenu['themes.php'][15]);
    }
}

/*
 * Capture user login and add it as timestamp in user meta data
 *
 */
 
function uamswp_user_last_login( $user_login, $user ) {
    update_user_meta( $user->ID, 'last_login', time() );
}
add_action( 'wp_login', 'uamswp_user_last_login', 10, 2 );
 
/*
 * Display last login time
 *
 */
  
function uamswp_lastlogin() { 
    $last_login = get_the_author_meta('last_login');
    $the_login_date = human_time_diff($last_login);
    return $the_login_date; 
} 
 
/*
 * Add Shortcode lastlogin 
 *
 */
  
add_shortcode('lastlogin','uamswp_lastlogin');

/*
 * Add user column 
 *
 */

function uamswp_modify_user_table( $column ) {
    $column['last_login'] = 'Last Login';
    return $column;
}
add_filter( 'manage_users_columns', 'uamswp_modify_user_table' );

function uamswp_modify_user_table_row( $val, $column_name, $user_id ) {
    switch ($column_name) {
        case 'last_login' :
            $the_login_date = __( 'Never', 'uamswp' );
            $last_login = get_the_author_meta('last_login', $user_id );
            if ($last_login)
                $the_login_date = human_time_diff($last_login);
            return $the_login_date; 
        default:
    }
    return $val;
}
add_filter( 'manage_users_custom_column', 'uamswp_modify_user_table_row', 10, 3 );