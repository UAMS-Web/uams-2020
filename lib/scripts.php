<?php
/**
 * Scripts
 *
 * @package      Bootstrap for Genesis
 * @since        1.0
 * @link         http://webdevsuperfast.github.io
 * @author       Rotsen Mark Acob <webdevsuperfast.github.io>
 * @copyright    Copyright (c) 2015, Rotsen Mark Acob
 * @license      http://opensource.org/licenses/gpl-2.0.php GNU Public License
 *
*/

// Theme Scripts & Stylesheet
add_action( 'wp_enqueue_scripts', 'uamswp_theme_scripts' );
function uamswp_theme_scripts() {
	$version = wp_get_theme()->Version;
	if ( !is_admin() ) {
		// Enqueue Bootstrap CSS
		wp_enqueue_style( 'app-css', UAMSWP_THEME_CSS . 'app.css' );

		// Enqueue Google Fonts
		wp_enqueue_style( 'google-fonts', '//fonts.googleapis.com/css?family=Fira+Sans:300,300i,500,500i,600,600i,800,800i', array(), 'CHILD_THEME_VERSION' );

		// Disable the superfish script
		wp_deregister_script( 'superfish' );
		wp_deregister_script( 'superfish-args' );

		// Deregister jQuery and use Bootstrap supplied version
		wp_deregister_script( 'jquery' );
		wp_register_script( 'jquery', UAMSWP_THEME_JS . 'jquery.min.js', array(), $version, true );
		wp_enqueue_script( 'jquery' );

		// Register Popper JS and enqueue it
		wp_register_script( 'app-popper-js', UAMSWP_THEME_JS . 'popper.min.js', array( 'jquery' ), $version, true );
		wp_enqueue_script( 'app-popper-js' );

		// Register Bootstrap JS and enqueue it
		wp_register_script( 'app-bootstrap-js', UAMSWP_THEME_JS . 'bootstrap.min.js', array( 'jquery' ), $version, true );
		wp_enqueue_script( 'app-bootstrap-js' );

		// Register Smart Menu JS and enqueue it
		wp_register_script( 'app-smartmenu-js', UAMSWP_THEME_JS . 'jquery.smartmenus.min.js', array( 'jquery' ), $version, true );
		wp_enqueue_script( 'app-smartmenu-js' );

		// Register Smart Menu Boostrap Addon Js and enqueue it
		wp_register_script( 'app-smartmenu-bootstrap-js', UAMSWP_THEME_JS . 'jquery.smartmenus.bootstrap-4.min.js', array( 'app-smartmenu-js' ), $version, true );
		wp_enqueue_script( 'app-smartmenu-bootstrap-js' );

		// Register Overflowing Navbar and enqueue it
		wp_register_script( 'app-overflowing-navbar-js', UAMSWP_THEME_JS . 'overflowing-navbar.min.js', array( 'jquery' ), $version, true );
		wp_enqueue_script( 'app-overflowing-navbar-js' );

		// Register Font Awesome JS and enqueue it
		wp_register_script( 'app-fontawesome-js', 'https://use.fontawesome.com/releases/v5.6.3/js/all.js', array(), $version, true );
		wp_enqueue_script( 'app-fontawesome-js' );

		// Register Font Awesome 4 Shim and enqueue it
		wp_register_script( 'app-fontawesome-shim-js', 'https://use.fontawesome.com/releases/v5.6.3/js/v4-shims.js', array( 'app-fontawesome-js', $version, true ) );
		wp_enqueue_script( 'app-fontawesome-shim-js' );

		// Register Search and Quick Links Trays JS and enqueue it
		wp_register_script( 'app-headertrays-js', UAMSWP_THEME_JS . 'headertrays.min.js', array( 'jquery' ), $version, true );
		wp_enqueue_script( 'app-headertrays-js' );

		// Register theme JS and enqueue it
		wp_register_script( 'app-js', UAMSWP_THEME_JS . 'app.min.js', array( 'jquery' ), $version, true );
		wp_enqueue_script( 'app-js' );
		
	}
}

// Editor Styles
add_action( 'init', 'uamswp_custom_editor_css' );
function uamswp_custom_editor_css() {
	add_editor_style( get_stylesheet_uri() );
}
