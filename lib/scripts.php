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
		wp_enqueue_style( 'google-fonts', '//fonts.googleapis.com/css?family=Fira+Sans:300,300i,500,500i,600,600i,800,800i&display=swap', array(), 'CHILD_THEME_VERSION' );

		// Disable the superfish script
		wp_deregister_script( 'superfish' );
		wp_deregister_script( 'superfish-args' );
		// Deregister Hover Intent
		wp_deregister_script( 'hoverIntent' );

		// Deregister jQuery and use Bootstrap supplied version
		// wp_deregister_script( 'jquery' );
		// wp_register_script( 'jquery', UAMSWP_THEME_JS . 'jquery.min.js', array(), $version, false );
		// wp_enqueue_script( 'jquery' );

		// Register theme JS and enqueue it
		wp_register_script( 'app-js', UAMSWP_THEME_JS . 'uams.min.js', array( 'jquery' ), $version, true ); // Renamed for dependencies
		wp_enqueue_script( 'app-js' );

		// // Register Popper JS and enqueue it
		// wp_register_script( 'app-popper-js', UAMSWP_THEME_JS . 'popper.min.js', array( 'jquery' ), $version, true );
		// wp_enqueue_script( 'app-popper-js' );

		// // Register Bootstrap JS and enqueue it
		// wp_register_script( 'app-bootstrap-js', UAMSWP_THEME_JS . 'bootstrap.min.js', array( 'jquery' ), $version, true );
		// wp_enqueue_script( 'app-bootstrap-js' );

		// // Register Smart Menu JS and enqueue it
		// wp_register_script( 'app-smartmenu-js', UAMSWP_THEME_JS . 'jquery.smartmenus.min.js', array( 'jquery' ), $version, true );
		// wp_enqueue_script( 'app-smartmenu-js' );

		// // Register Smart Menu Boostrap Addon Js and enqueue it
		// wp_register_script( 'app-smartmenu-bootstrap-js', UAMSWP_THEME_JS . 'jquery.smartmenus.bootstrap-4.min.js', array( 'app-smartmenu-js' ), $version, true );
		// wp_enqueue_script( 'app-smartmenu-bootstrap-js' );

		// // Register Overflowing Navbar and enqueue it
		// wp_register_script( 'app-overflowing-navbar-js', UAMSWP_THEME_JS . 'overflowing-navbar.min.js', array( 'jquery' ), $version, true );
		// wp_enqueue_script( 'app-overflowing-navbar-js' );

		// Register Font Awesome JS and enqueue it
		// wp_register_script( 'app-fontawesome-js', 'https://use.fontawesome.com/releases/v5.6.3/js/all.js', array(), $version, true );
		// wp_enqueue_script( 'app-fontawesome-js' );

		// // Register Font Awesome 4 Shim and enqueue it
		// wp_register_script( 'app-fontawesome-shim-js', 'https://use.fontawesome.com/releases/v5.6.3/js/v4-shims.js', array( 'app-fontawesome-js', $version, true ) );
		// wp_enqueue_script( 'app-fontawesome-shim-js' );

		// // Register Search and Quick Links Trays JS and enqueue it
		// wp_register_script( 'app-headertrays-js', UAMSWP_THEME_JS . 'headertrays.min.js', array( 'jquery' ), $version, true );
		// wp_enqueue_script( 'app-headertrays-js' );

		// // Register theme JS and enqueue it
		// wp_register_script( 'app-js', UAMSWP_THEME_JS . 'app.min.js', array( 'jquery' ), $version, true );
		// wp_enqueue_script( 'app-js' );

		// // Register Font Awesome JS and enqueue it
		wp_register_script( 'fa-js', UAMSWP_THEME_JS . 'fa.min.js', array( ), $version, true );
		wp_enqueue_script( 'fa-js' );

		// Alert system based on uams-2016
		wp_enqueue_script( 'uams-alert', get_stylesheet_directory_uri() . '/assets/js/uamsalert.js', array(), '2.5.0', true );
		// wp_enqueue_style( 'uams-alert-style', get_stylesheet_directory_uri() . '/assets/css/uams.alert.css', array(), '1.0.0', 'all' );

		// Deregister shourtcode-ui styles
		wp_deregister_style( 'shortcodes_styles' );
		
	}
}

// Editor Styles
add_action( 'init', 'uamswp_custom_editor_css' );
function uamswp_custom_editor_css() {
	add_editor_style( get_stylesheet_uri() );
}


// CORS header fix
add_filter('allowed_http_origins', 'add_allowed_origins');

function add_allowed_origins($origins) {
    $origins[] = 'https://www.uams.edu';
    $origins[] = 'http://www.uams.edu';
	$origins[] = 'https://public-api.wordpress.com';
	$origins[] = 'https://uamshealth.com';
	$origins[] = 'https://news.uams.edu';
    return $origins;
}

// function defer_parsing_of_js( $url ) {
//     if ( is_user_logged_in() ) return $url; //don't break WP Admin
//     if ( FALSE === strpos( $url, '.js' ) ) return $url;
//     if ( strpos( $url, 'jquery' ) ) return $url; // Skip jquery file(s)
//     return str_replace( ' src', ' defer src', $url );
// }
// add_filter( 'script_loader_tag', 'defer_parsing_of_js', 10 );

// function add_rel_preload($html, $handle, $href, $media) {
    
//     if (is_admin())
//         return $html;

//      $html = <<<EOT
// <link rel='preload' as='style' onload="this.onload=null;this.rel='stylesheet'" id='$handle' href='$href' type='text/css' media='all' />
// EOT;
//     return $html;
// }
// add_filter( 'style_loader_tag', 'add_rel_preload', 10, 4 );

// function hook_css() {
//     include get_theme_file_path( '/assets/css/critical.css');
// }
// add_action('wp_head', 'hook_css');

/**
 * Gutenberg scripts and styles
 *
 */
function uamswp_gutenberg_scripts() {
	wp_enqueue_style( 'theme-fonts', uamswp_theme_fonts_url() );
	wp_enqueue_script( 'theme-editor', get_stylesheet_directory_uri() . '/assets/js/editor.js', array( 'wp-blocks', 'wp-dom' ), filemtime( get_stylesheet_directory_uri() . '/assets/js/editor.js' ), true );
}
add_action( 'enqueue_block_editor_assets', 'uamswp_gutenberg_scripts' );

/**
 * Theme Fonts URL
 *
 */
function uamswp_theme_fonts_url() {
	return 'https://fonts.googleapis.com/css2?family=Fira+Sans&display=swap';
}