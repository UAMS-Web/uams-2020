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
	add_theme_support( 'genesis-footer-widgets', 1 ); 

	// Custom Logo
	add_theme_support( 'custom-logo', array(
		'flex-width' => true,
		'flex-height' => true
	) );

	// Structural Wraps
	add_theme_support( 'genesis-structural-wraps', array(
		//'header',
		// 'site-inner',
		// 'footer-widgets',
		//'footer',
		//'home-featured'
	) );

	// WooCommerce Support
	add_theme_support( 'genesis-connect-woocommerce' );

	// Responsive Embeds
	add_theme_support( 'responsive-embeds' );

	// Gutenberg Support for block editor
	add_theme_support( 'align-wide' );

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
	add_image_size( 'aspect-16-9', 1024, 576, true );
	add_image_size( 'aspect-16-9-small', 512, 288, true );
	add_image_size( 'aspect-8-3', 1024, 384, true );
	//add_image_size( 'aspect-8-3-small', 512, 192, true ); // hidden until needed
	add_image_size( 'aspect-4-3', 1024, 768, true );
	//add_image_size( 'aspect-4-3-small', 512, 384, true ); // hidden until needed
	add_image_size( 'aspect-2-1', 1024, 512, true );
	//add_image_size( 'aspect-2-1-small', 512, 256, true ); // hidden until needed
	add_image_size( 'aspect-1-1', 1024, 1024, true );
	//add_image_size( 'aspect-1-1-small', 512, 512, true ); // hidden until needed
	add_image_size( 'hero-tablet', 455, 256, true );
	add_image_size( 'content-image-side', 299, 9999 );
	add_image_size( 'content-image-center', 630, 9999 );
	add_image_size( 'content-image-wide', 1020, 9999 );
	add_image_size( 'content-image-full', 1920, 9999 );

	// Add custom image sizes to post editor

	add_filter( 'image_size_names_choose', 'uams_custom_add_image_size_names' );
	function uams_custom_add_image_size_names( $sizes ) {
	return array_merge( $sizes, array(
		'content-image-side' => __( 'Content image aligned left/right' ),
		'content-image-center' => __( 'Content image aligned center' ),
		'content-image-wide' => __( 'Content image aligned wide' ),
		'content-image-full' => __( 'Content image aligned full' ),
	) );
	}


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
		$crumb = '<a href="'.uams_get_home_link().'" title="University of Arkansas for Medical Scineces"><span class="fas fa-home"></span></a></li><li class="breadcrumb-item">'.uams_site_title().'';
	} else {
	 	$crumb = '<a href="'.uams_get_home_link().'" title="University of Arkansas for Medical Scineces"><span class="fas fa-home"></span></a></li><li class="breadcrumb-item"><a href="' . home_url() . '" title="' . uams_site_title() . '">'.uams_site_title().'</a>';
	}
     return $crumb;
}

/** Returns site & subsite info **/
if ( !function_exists('uams_get_site_info')):

    function uams_get_site_info()
    {
		$site = '';
		$subsite = '';
		$option_name = 'uamswp_options'; // Settings page
		$siteinfo = array();
		if ( ! class_exists( 'acf' ) ) {
			// Set base defaults if no ACF
			$siteinfo = array('site' => 'uams', 'subsite' => 'uams');
			return $siteinfo;
			return;
		}
		$themestyle = get_field( 'uamswp_template', 'option' ); // uams, inside, health
		$themelocation = get_field( 'uamswp_location', 'option' ); // campus, regional
		$themeinstitute = get_field( 'uamswp_institute', 'option' ); // institute name
		$uamsorganization = get_field( 'uamswp_uams_subsite', 'option' ); // college 
		$healthorganization = get_field( 'uamswp_uamshealth_subsite', 'option' ); // health 
		$insideorganization = get_field( 'uamswp_inside_subsite', 'option' ); // inside 
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
  return preg_replace("/([0-9]{1})([0-9]{3})([0-9]{3})([0-9]{4})/", "($2) $3-$4", $phone); // Removed country code
  break;
  default:
    return $phone;
  break;
  }
}
 
function format_phone_dash($phone) {
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
   return preg_replace("/([0-9]{3})([0-9]{3})([0-9]{4})/", "$1-$2-$3", $phone);
  break;
  case 11:
  return preg_replace("/([0-9]{1})([0-9]{3})([0-9]{3})([0-9]{4})/", "$2-$3-$4", $phone); // Removed country code
  break;
  default:
    return $phone;
  break;
  }
}

/**
 * Return sized image.
 *
 * @param integer  $id 			// id of image
 * @param integer  $prefwidth	// Preferred Output width
 * @param string   $prefheight	// Preferred Output height
 * @param string   $hcrop		// horizontal crop position (left, center, right)
 * @param string   $vcrop		// vertical crop position (top, center, bottom)
 * @return string				// image url
 */
function image_sizer( $id, $prefwidth, $prefheight, $hcrop = 'center', $vcrop = 'center' ) {

	if ( ! function_exists( 'fly_add_image_size' ) ) {
		return;
	}
	if ( ! $id ) {
		return; // Make sure we have value
	}
	$image_width = wp_get_attachment_image_src($id, 'full')[1];
	$image_height = wp_get_attachment_image_src($id, 'full')[2];
	// Do the maths
	$image_ratio = $image_width / $image_height;
	$pref_ratio = $prefwidth / $prefheight;
	if( $image_width >= $prefwidth && $image_height >= $prefheight ) { // Bigger image => Crop
		$image_url = fly_get_attachment_image_src( $id, array( $prefwidth, $prefheight ), array( $hcrop, $vcrop ) )['src'];
	} elseif ( $image_ratio > $pref_ratio ) { // wide image => figure out max crop
		$prefwidth = $image_width;
		$prefheight = $image_width / $pref_ratio;
		if( $prefheight > $image_height ) {
			$prefheight = $image_height;
			$prefwidth = $prefheight * $pref_ratio;
		}
		$image_url = fly_get_attachment_image_src( $id, array( $prefwidth, $prefheight ), array( $hcrop, $vcrop ) )['src'];
	} elseif ( $image_ratio < $pref_ratio ) { // tall image => figure out max crop
		$prefwidth = $image_height * $pref_ratio;
		$prefheight = $image_height;
		if( $prefwidth > $image_width ) {
			$prefwidth = $image_width;
			$prefheight = $prefwidth / $pref_ratio;
		}
		$image_url = fly_get_attachment_image_src( $id, array( $prefwidth, $prefheight ), array( $hcrop, $vcrop ) )['src'];
	} else { // Perfect ratio => no crop, return orig
		$image_url = wp_get_attachment_url( $id, 'full' );
	}
	return $image_url;
}

//
/* Add dynamic_sidebar_params filter */
add_filter('dynamic_sidebar_params','footer_widgets');
 
/* Register our callback function */
function footer_widgets($params) {	 
  
     //Check if we are displaying "Footer Sidebar"
      if(isset($params[0]['id']) && $params[0]['id'] == 'footer-1'){
 
         //If widget is not uams widget, add class
		if (strpos($params[0]['before_widget'], 'uamswp') == false) {
	    $class = 'class="uams-module '; 
	    $params[0]['before_widget'] = str_replace('class="', $class, $params[0]['before_widget']);
	  	}
 
	}
 
      return $params;
}

add_action( 'admin_enqueue_scripts', 'enqueue_admin_style_sheet' );
function enqueue_admin_style_sheet() {

	wp_register_style( 'admin-css', get_stylesheet_directory_uri() . '/assets/css/admin.css', false, '1.0.0' );
	wp_enqueue_style( 'admin-css' );

}

// Remove the edit link
add_filter ( 'genesis_edit_post_link' , '__return_false' );

// Add REST API Filter for local sites. Used for local News Syndication
add_action( 'rest_api_init', 'rest_api_filter_add_filters' );
 /**
  * Add the necessary filter to each post type
  **/
function rest_api_filter_add_filters() {
	foreach ( get_post_types( array( 'show_in_rest' => true ), 'objects' ) as $post_type ) {
		add_filter( 'rest_' . $post_type->name . '_query', 'rest_api_filter_add_filter_param', 10, 2 );
	}
}
/**
 * Add the filter parameter
 *
 * @param  array           $args    The query arguments.
 * @param  WP_REST_Request $request Full details about the request.
 * @return array $args.
 **/
function rest_api_filter_add_filter_param( $args, $request ) {
	// Bail out if no filter parameter is set.
	if ( empty( $request['filter'] ) || ! is_array( $request['filter'] ) ) {
		return $args;
	}
	$filter = $request['filter'];
	if ( isset( $filter['posts_per_page'] ) && ( (int) $filter['posts_per_page'] >= 1 && (int) $filter['posts_per_page'] <= 100 ) ) {
		$args['posts_per_page'] = $filter['posts_per_page'];
	}
	global $wp;
	$vars = apply_filters( 'rest_query_vars', $wp->public_query_vars );
	// Allow valid meta query vars.
	$vars = array_unique( array_merge( $vars, array( 'meta_query', 'meta_key', 'meta_value', 'meta_compare' ) ) );
	foreach ( $vars as $var ) {
		if ( isset( $filter[ $var ] ) ) {
			$args[ $var ] = $filter[ $var ];
		}
	}
	return $args;
}
//Gravity Forms JS to footer
add_filter('gform_init_scripts_footer', '__return_true');