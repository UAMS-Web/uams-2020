<?php
/**
 * Functions
 *
 * @package      Bootstrap for Genesis
 * @since        1.0
 * @link         http://webdevsuperfast.github.io
 * @author       Rotsen Mark Acob <webdevsuperfast.github.io>
 * @copyright    Copyright (c) 2015, Rotsen Mark Acob
 * @license      http://opensource.org/licenses/gpl-2.0.php GNU Public License
 *
*/

add_action( 'genesis_setup', 'uamswp_childtheme_setup', 15 );

function uamswp_childtheme_setup() {
	// Start the engine
	include_once( get_template_directory() . '/lib/init.php' );

	// Child theme (do not remove)
	define( 'UAMSWP_THEME_NAME', 'Bootstrap for Genesis' );
	define( 'UAMSWP_THEME_URL', 'http://webdevsuperfast.github.io/' );
	define( 'UAMSWP_THEME_LIB', CHILD_DIR . '/lib/' );
	define( 'UAMSWP_THEME_LIB_URL', CHILD_URL . '/lib/' );
	define( 'UAMSWP_THEME_IMAGES', CHILD_URL . '/images/' );
	define( 'UAMSWP_THEME_JS', CHILD_URL . '/assets/js/' );
	define( 'UAMSWP_THEME_CSS', CHILD_URL . '/assets/css/' );
	define( 'UAMSWP_THEME_MODULES', CHILD_DIR . '/lib/modules/' );

	// Cleanup WP Head
	remove_action( 'wp_head', 'rsd_link' );
	remove_action( 'wp_head', 'wlwmanifest_link' );
	remove_action( 'wp_head', 'wp_generator' );
	remove_action( 'wp_head', 'start_post_rel_link' );
	remove_action( 'wp_head', 'index_rel_link' );
	remove_action( 'wp_head', 'adjacent_posts_rel_link' );
	remove_action( 'wp_head', 'wp_shortlink_wp_head' );

	// Add HTML5 markup structure
	add_theme_support( 'html5', array( 'search-form', 'comment-form', 'comment-list' ) );

	//* Unregister secondary navigation menu
	add_theme_support( 'genesis-menus', array( 'primary' => __( 'Primary Navigation Menu', 'genesis' ) ) );

	// Add viewport meta tag for mobile browsers
	add_theme_support( 'genesis-responsive-viewport' );

	// Add support for 3-column footer widgets
	add_theme_support( 'genesis-footer-widgets', 3 );

	// Custom Logo
	add_theme_support( 'custom-logo', array(
		'flex-width' => true,
		'flex-height' => true
	) );

	// Structural Wraps
	add_theme_support( 'genesis-structural-wraps', array(
		//'header',
		'site-inner',
		'footer-widgets',
		//'footer',
		'home-featured'
	) );

	// WooCommerce Support
	add_theme_support( 'genesis-connect-woocommerce' );

	// Remove unneeded widget areas
	unregister_sidebar( 'header-right' );

	// Move Sidebar Secondary After Content
	remove_action( 'genesis_after_content_sidebar_wrap', 'genesis_get_sidebar_alt' );
	add_action( 'genesis_after_content', 'genesis_get_sidebar_alt' );

	// Remove Gallery CSS
	add_filter( 'use_default_gallery_style', '__return_false' );

	// Add Shortcode Functionality to Text Widgets
	add_filter( 'widget_text', 'shortcode_unautop' );
	add_filter( 'widget_text', 'do_shortcode' );

	// Move Featured Image
	remove_action( 'genesis_entry_content', 'genesis_do_post_image', 8 );
	add_action( 'genesis_entry_header',  'genesis_do_post_image', 0 );

	// Custom Image Size
	add_image_size( 'bootstrap-featured', 730, 0, true );

	// Add Accessibility support
	add_theme_support( 'genesis-accessibility', array( '404-page', 'drop-down-menu', 'headings', 'rems', 'search-form', 'skip-links' ) );

	// TGM Plugin Activation
	require_once( UAMSWP_THEME_MODULES . 'class-tgm-plugin-activation.php' );

	// Include php files from lib folder
	// @link https://gist.github.com/theandystratton/5924570
	foreach ( glob( dirname( __FILE__ ) . '/lib/*.php' ) as $file ) {
		include $file;
	}
	
	// Load Child theme text domain
	load_child_theme_textdomain( 'uams-2020', get_stylesheet_directory() . '/languages' );
}

// Add extra class to content
// https://www.wpstud.io/add-extra-class-to-html-elements-in-genesis/
//function wpstudio_add_class( $attributes ) {
//	$attributes['class'] = $attributes['class']. ' container-fluid';
//		return $attributes;
//	}
//	
//	add_filter( 'genesis_attr_site-header', 'wpstudio_add_class' );

add_filter ( 'genesis_home_crumb', 'uams_breadcrumb_home_icon' ); 
function uams_breadcrumb_home_icon( $crumb ) {
	if (is_front_page()) {
		$crumb = '<a href="'.$homelink.'" title="University of Arkansas for Medical Scineces"><span class="fas fa-home"></span></a></li><li class="breadcrumb-item">'.uams_site_title().'';
	} else {
	 	$crumb = '<a href="'.$homelink.'" title="University of Arkansas for Medical Scineces"><span class="fas fa-home"></span></a></li><li class="breadcrumb-item"><a href="' . home_url() . '" title="' . uams_site_title() . '">'.uams_site_title().'</a>';
	}
     return $crumb;
}

/** Returns site & subsite info **/
if ( !function_exists('uams_get_site_info')):

    function uams_get_site_info()
    {
		$option_name = 'uamswp_options'; // Settings page
		$siteinfo = array();
		$themestyle = rwmb_meta( 'uamswp_template', array( 'object_type' => 'setting' ), $option_name ); // uams, inside, health
		$themelocation = rwmb_meta( 'uamswp_location', array( 'object_type' => 'setting' ), $option_name ); // campus, regional
		$themeinstitute = rwmb_meta( 'uamswp_institute', array( 'object_type' => 'setting' ), $option_name ); // institute name
		$uamsorganization = rwmb_meta( 'uamswp_uams_subsite', array( 'object_type' => 'setting' ), $option_name ); // college 
		$healthorganization = rwmb_meta( 'uamswp_uamshealth_subsite', array( 'object_type' => 'setting' ), $option_name ); // college 
		$insideorganization = rwmb_meta( 'uamswp_inside_subsite', array( 'object_type' => 'setting' ), $option_name ); // college 
		if ('health' == $themestyle) {
			$site = "uamshealth";
			if ('' != $healthorganization) {
				$subsite = $healthorganization;
			} else {
				$subsite = "uams";
			}
		} elseif ('inside' == $themestyle) {
			$site = "inside";
			if ('' != $insideorganization) {
				$subsite = $insideorganization;
			} else {
				$subsite = "uams";
			}
		} elseif ('institute' == $themestyle) {
			$site = "institute";
			if ('' != $themeinstitute) {
				$subsite = $themeinstitute;
			} else {
				$subsite = "uams";
			}
		} elseif ('uams' == $themestyle) {
			$site = "uams";
			if ('' != $themelocation){
				if ('uams' != $themelocation) {
					$subsite = $themelocation;
				} else {
					if ('' != $uamsorganization) {
						$subsite = $uamsorganization;
					} else {
						$subsite = "uams";
					}
				}
			} else {
				$subsite = "uams";
			}
		}
		$siteinfo = array('site' => $site, 'subsite' => $subsite);
        return $siteinfo;
    }

endif;

if ( !function_exists('uams_get_permalink')):

	function uams_get_permalink( $post = null )
	{
		if (empty($post)){
		  $postID = get_the_ID();
		} else {
		  $post = get_post( $post );
		  $postID = $post->ID;
		}
		$external_url = get_post_meta( $postID, 'post_custom_link', true);
		$post_format = get_post_format( $postID );
		if (!empty($external_url) && ($post_format == 'link')){
			$link = $external_url;
		} else {
			$link = get_permalink( $postID );
		}
		return $link;
	}
  
endif;

/* Retrieve site site */
if ( !function_exists('uams_site_title')):

    function uams_site_title()
    {
		if ('uamshealth' == uams_get_site_info()['site']) {
			return 'UAMS Health';
		} else {
			return get_bloginfo( 'name' );
		}
    }

endif;

/* returns home link for breadcrumbs, logo & anywhere else */
if ( !function_exists('uams_get_home_link')):

    function uams_get_home_link()
    {
		if (('uams' == uams_get_site_info()['site']) || ('institute' == uams_get_site_info()['site'])) {
			$homelink = 'http://www.uams.edu';
		} elseif ('uamshealth' == uams_get_site_info()['site']) {
			$homelink = 'https://uamshealth.com';
		} elseif ('inside' == uams_get_site_info()['site']) {
			$homelink = 'http://inside.uams.edu';
		}
		return $homelink;
    }

endif;

/* Helper Functions */
function startsWith($haystack, $needle)
{
     $length = strlen($needle);
     return (substr($haystack, 0, $length) === $needle);
}

function format_phone($country, $phone) {
  $function = 'format_phone_' . $country;
  if(function_exists($function)) {
    return $function($phone);
  }
  return $phone;
}

// usage
// $phone = format_phone('us', $phone);
// echo $phone;

function format_phone_us($phone) {
  // note: making sure we have something
  if(!isset($phone{3})) { return ''; }
  // note: strip out everything but numbers 
  $phone = preg_replace("/[^0-9]/", "", $phone);
  $length = strlen($phone);
  switch($length) {
  case 7:
    return preg_replace("/([0-9]{3})([0-9]{4})/", "$1-$2", $phone);
  break;
  case 10:
   return preg_replace("/([0-9]{3})([0-9]{3})([0-9]{4})/", "($1) $2-$3", $phone);
  break;
  case 11:
  return preg_replace("/([0-9]{1})([0-9]{3})([0-9]{3})([0-9]{4})/", "$1($2) $3-$4", $phone);
  break;
  default:
    return $phone;
  break;
  }
}
 
