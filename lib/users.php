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