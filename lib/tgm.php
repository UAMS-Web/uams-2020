<?php
/**
 * TGM Plugin
 *
 * @package      Bootstrap for Genesis
 * @since        1.0
 * @link         http://webdevsuperfast.github.io
 * @author       Rotsen Mark Acob <webdevsuperfast.github.io>
 * @copyright    Copyright (c) 2015, Rotsen Mark Acob
 * @license      http://opensource.org/licenses/gpl-2.0.php GNU Public License
 *
*/

// TGM Plugin Activation
add_action( 'tgmpa_register', 'uamswp_do_plugins_register' );
function uamswp_do_plugins_register() {

	$plugins = array(
		array(
			'name'      => __( 'Widget CSS Classes', 'uams-2020' ),
			'slug'      => 'widget-css-classes',
			'required'  => false,
		),
		array(
			'name'      => __( 'ACF Blocks', 'uamswp-uams-2020' ),
			'slug'      => 'acf-blocks',
			'required'  => true,
		),

		array(
			'name'      => __( 'Fly Dynamic Image Resizer', 'uamswp-uams-2020' ),
			'slug'      => 'fly-dynamic-image-resizer',
			'required'  => true,
		),

		array(
			'name'      => __( 'UAMSWP YouTube Lyte', 'uamswp-uams-2020' ),
			'slug'      => 'uamswp-youtube-lyte',
			'source'             => get_stylesheet_directory() . '/plugins/uamswp-youtube-lyte.zip', // The plugin source.
			'required'  => true,
		),

		// array(
		// 	'name'      => __( 'Meta Box Text Limiter', 'uamswp-uams-2020' ),
		// 	'slug'      => 'meta-box-text-limiter',
		// 	'required'  => false,
		// ),
		// Begin ACF extensions.
		array(
			'name'               => __( 'Advanced Custom Fields PRO', 'uamswp-uams-2020' ), // The plugin name.
			'slug'               => 'advanced-custom-fields-pro', // The plugin slug (typically the folder name).
			'source'             => get_stylesheet_directory() . '/plugins/advanced-custom-fields-pro.zip', // The plugin source.
			'required'           => true, // If false, the plugin is only 'recommended' instead of required.
		),
		array(
			'name'               => __( 'Advanced Custom Fields: Mask Field', 'uamswp-uams-2020' ), // The plugin name.
			'slug'               => 'acf-maskfield', // The plugin slug (typically the folder name).
			'source'             => get_stylesheet_directory() . '/plugins/acf-maskfield.zip', // The plugin source.
			'required'           => true, // If false, the plugin is only 'recommended' instead of required.
		),

		// array(
		// 	'name'               => __( 'MB Admin Columns', 'uamswp-uams-2020' ), // The plugin name.
		// 	'slug'               => 'mb-admin-columns', // The plugin slug (typically the folder name).
		// 	'source'             => get_stylesheet_directory() . '/plugins/mb-admin-columns.zip', // The plugin source.
		// 	'required'           => false, // If false, the plugin is only 'recommended' instead of required.
		// ),

		// array(
		// 	'name'               => __( 'MB Term Meta', 'uamswp-uams-2020' ), // The plugin name.
		// 	'slug'               => 'mb-term-meta', // The plugin slug (typically the folder name).
		// 	'source'             => get_stylesheet_directory() . '/plugins/mb-term-meta.zip', // The plugin source.
		// 	'required'           => false, // If false, the plugin is only 'recommended' instead of required.
		// ),

		// array(
		// 	'name'               => __( 'MB Custom Table', 'uamswp-uams-2020' ), // The plugin name.
		// 	'slug'               => 'mb-custom-table', // The plugin slug (typically the folder name).
		// 	'source'             => get_stylesheet_directory() . '/plugins/mb-custom-table.zip', // The plugin source.
		// 	'required'           => false, // If false, the plugin is only 'recommended' instead of required.
		// ),

		// array(
		// 	'name'               => __( 'Meta Box Group', 'uamswp-uams-2020' ), // The plugin name.
		// 	'slug'               => 'meta-box-group', // The plugin slug (typically the folder name).
		// 	'source'             => get_stylesheet_directory() . '/plugins/meta-box-group.zip', // The plugin source.
		// 	'required'           => true, // If false, the plugin is only 'recommended' instead of required.
		// ),

		// array(
		// 	'name'               => __( 'Meta Box Columns', 'uamswp-uams-2020' ), // The plugin name.
		// 	'slug'               => 'meta-box-columns', // The plugin slug (typically the folder name).
		// 	'source'             => get_stylesheet_directory() . '/plugins/meta-box-columns.zip', // The plugin source.
		// 	'required'           => false, // If false, the plugin is only 'recommended' instead of required.
		// ),

		// array(
		// 	'name'               => __( 'Meta Box Updater', 'uamswp-uams-2020' ), // The plugin name.
		// 	'slug'               => 'meta-box-updater', // The plugin slug (typically the folder name).
		// 	'source'             => get_stylesheet_directory() . '/plugins/meta-box-updater.zip', // The plugin source.
		// 	'required'           => true, // If false, the plugin is only 'recommended' instead of required.
		// ),

		// array(
		// 	'name'               => __( 'MB Settings Page', 'uamswp-uams-2020' ), // The plugin name.
		// 	'slug'               => 'mb-settings-page', // The plugin slug (typically the folder name).
		// 	'source'             => get_stylesheet_directory() . '/plugins/mb-settings-page.zip', // The plugin source.
		// 	'required'           => true, // If false, the plugin is only 'recommended' instead of required.
		// ),

		// array(
		// 	'name'               => __( 'MB Revision', 'uamswp-uams-2020' ), // The plugin name.
		// 	'slug'               => 'mb-revision', // The plugin slug (typically the folder name).
		// 	'source'             => get_stylesheet_directory() . '/plugins/mb-revision.zip', // The plugin source.
		// 	'required'           => true, // If false, the plugin is only 'recommended' instead of required.
		// ),

		// array(
		// 	'name'               => __( 'Meta Box Conditional Logic', 'uamswp-uams-2020' ), // The plugin name.
		// 	'slug'               => 'meta-box-conditional-logic', // The plugin slug (typically the folder name).
		// 	'source'             => get_stylesheet_directory() . '/plugins/meta-box-conditional-logic.zip', // The plugin source.
		// 	'required'           => true, // If false, the plugin is only 'recommended' instead of required.
		// ),

		// array(
		// 	'name'               => __( 'Meta Box Include Exclude', 'uamswp-uams-2020' ), // The plugin name.
		// 	'slug'               => 'meta-box-include-exclude', // The plugin slug (typically the folder name).
		// 	'source'             => get_stylesheet_directory() . '/plugins/meta-box-includeexclude.zip', // The plugin source.
		// 	'required'           => true, // If false, the plugin is only 'recommended' instead of required.
		// ),
	);

	$config = array(
		'id'           => 'uamswp', 			   // Unique ID for hashing notices for multiple instances of TGMPA.
		'default_path' => '',                      // Default absolute path to pre-packaged plugins.
		'menu'         => 'tgmpa-install-plugins', // Menu slug.
		'has_notices'  => true,                    // Show admin notices or not.
		'dismissable'  => true,                    // If false, a user cannot dismiss the nag message.
		'dismiss_msg'  => '',                      // If 'dismissable' is false, this message will be output at top of nag.
		'is_automatic' => false,                   // Automatically activate plugins after installation or not.
		'message'      => '',                      // Message to output right before the plugins table.
		'strings'      => array(
			'page_title'                      => __( 'Install Required Plugins', 'tgmpa' ),
			'menu_title'                      => __( 'Install Plugins', 'tgmpa' ),
			'installing'                      => __( 'Installing Plugin: %s', 'tgmpa' ), // %s = plugin name.
			'oops'                            => __( 'Something went wrong with the plugin API.', 'tgmpa' ),
			'notice_can_install_required'     => _n_noop( 'This theme requires the following plugin: %1$s.', 'This theme requires the following plugins: %1$s.' ), // %1$s = plugin name(s).
			'notice_can_install_recommended'  => _n_noop( 'This theme recommends the following plugin: %1$s.', 'This theme recommends the following plugins: %1$s.' ), // %1$s = plugin name(s).
			'notice_cannot_install'           => _n_noop( 'Sorry, but you do not have the correct permissions to install the %s plugin. Contact the administrator of this site for help on getting the plugin installed.', 'Sorry, but you do not have the correct permissions to install the %s plugins. Contact the administrator of this site for help on getting the plugins installed.' ), // %1$s = plugin name(s).
			'notice_can_activate_required'    => _n_noop( 'The following required plugin is currently inactive: %1$s.', 'The following required plugins are currently inactive: %1$s.' ), // %1$s = plugin name(s).
			'notice_can_activate_recommended' => _n_noop( 'The following recommended plugin is currently inactive: %1$s.', 'The following recommended plugins are currently inactive: %1$s.' ), // %1$s = plugin name(s).
			'notice_cannot_activate'          => _n_noop( 'Sorry, but you do not have the correct permissions to activate the %s plugin. Contact the administrator of this site for help on getting the plugin activated.', 'Sorry, but you do not have the correct permissions to activate the %s plugins. Contact the administrator of this site for help on getting the plugins activated.' ), // %1$s = plugin name(s).
			'notice_ask_to_update'            => _n_noop( 'The following plugin needs to be updated to its latest version to ensure maximum compatibility with this theme: %1$s.', 'The following plugins need to be updated to their latest version to ensure maximum compatibility with this theme: %1$s.' ), // %1$s = plugin name(s).
			'notice_cannot_update'            => _n_noop( 'Sorry, but you do not have the correct permissions to update the %s plugin. Contact the administrator of this site for help on getting the plugin updated.', 'Sorry, but you do not have the correct permissions to update the %s plugins. Contact the administrator of this site for help on getting the plugins updated.' ), // %1$s = plugin name(s).
			'install_link'                    => _n_noop( 'Begin installing plugin', 'Begin installing plugins' ),
			'activate_link'                   => _n_noop( 'Begin activating plugin', 'Begin activating plugins' ),
			'return'                          => __( 'Return to Required Plugins Installer', 'tgmpa' ),
			'plugin_activated'                => __( 'Plugin activated successfully.', 'tgmpa' ),
			'complete'                        => __( 'All plugins installed and activated successfully. %s', 'tgmpa' ), // %s = dashboard link.
			'nag_type'                        => 'updated' // Determines admin notice type - can only be 'updated', 'update-nag' or 'error'.
		)
	);

	tgmpa( $plugins, $config );

}
