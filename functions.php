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

if ( ! defined( 'CHILD_THEME_VERSION' ) ) {
    define( 'CHILD_THEME_VERSION', wp_get_theme()->get( 'Version' ) );
}

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

	// Disable REST API link tag
	remove_action('wp_head', 'rest_output_link_wp_head', 10);
	// Disable oEmbed Discovery Links
	remove_action('wp_head', 'wp_oembed_add_discovery_links', 10);
	// Disable REST API link in HTTP headers
	remove_action('template_redirect', 'rest_output_link_header', 11, 0);

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

	//* Add Excerpt support to Pages in Genesis
	add_post_type_support( 'page', 'excerpt' ); 

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

// Custom Skip links - add aria and role
remove_action ( 'genesis_before_header', 'genesis_skip_links', 5 );
add_action( 'genesis_before_header', 'uamswp_skip_links', 5 );
/**
 * Add skip links for screen readers and keyboard navigation.
 *
 * @since 2.2.0
 *
 * @return void Return early if skip links are not supported.
 */
function uamswp_skip_links() {

	if ( ! genesis_a11y( 'skip-links' ) ) {
		return;
	}

	// Call function to add IDs to the markup.
	genesis_skiplinks_markup();

	// Determine which skip links are needed.
	$links = [];

	if ( genesis_nav_menu_supported( 'primary' ) && has_nav_menu( 'primary' ) ) {
		$links['genesis-nav-primary'] = esc_html__( 'Skip to primary navigation', 'genesis' );
	}

	$links['genesis-content'] = esc_html__( 'Skip to main content', 'genesis' );

	if ( 'full-width-content' !== genesis_site_layout() ) {
		$links['genesis-sidebar-primary'] = esc_html__( 'Skip to primary sidebar', 'genesis' );
	}

	if ( in_array( genesis_site_layout(), [ 'sidebar-sidebar-content', 'sidebar-content-sidebar', 'content-sidebar-sidebar' ], true ) ) {
		$links['genesis-sidebar-secondary'] = esc_html__( 'Skip to secondary sidebar', 'genesis' );
	}

	if ( current_theme_supports( 'genesis-footer-widgets' ) ) {
		$footer_widgets = get_theme_support( 'genesis-footer-widgets' );
		if ( isset( $footer_widgets[0] ) && is_numeric( $footer_widgets[0] ) && is_active_sidebar( 'footer-1' ) ) {
			$links['genesis-footer-widgets'] = esc_html__( 'Skip to footer', 'genesis' );
		}
	}

	/**
	 * Filter the skip links.
	 *
	 * @since 2.2.0
	 *
	 * @param array $links {
	 *     Default skiplinks.
	 *
	 *     @type string HTML ID attribute value to link to.
	 *     @type string Anchor text.
	 * }
	 */
	$links = (array) apply_filters( 'genesis_skip_links_output', $links );

	// Write HTML, skiplinks in a list.
	$skiplinks = '<nav aria-label="Skip links"><ul class="genesis-skip-link">';

	// Add markup for each skiplink.
	foreach ( $links as $key => $value ) {
		$skiplinks .= '<li><a href="' . esc_url( '#' . $key ) . '" class="screen-reader-shortcut"> ' . $value . '</a></li>';
	}

	$skiplinks .= '</ul></nav>';

	// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	echo $skiplinks;

}

add_filter( 'genesis_attr_site-header', 'uamswp_add_aria' );
function uamswp_add_aria( $attributes ) {
 if ( isset($attributes['aria-label']) ) {
	$attributes['aria-label'] = $attributes['aria-label']. 'Site Header';
 } else {
	$attributes['aria-label'] = 'Site Header';
 }
 return $attributes;
}

add_filter ( 'genesis_home_crumb', 'uams_breadcrumb_home_icon' );
function uams_breadcrumb_home_icon( $crumb ) {
		if (('uamshealth' == uams_get_site_info()['site'] && 'main' == uams_get_site_info()['subsite']) || ('uamshealth' == uams_get_site_info()['site'] && is_front_page() ) ) {
			$crumb = '<a href="'.uams_get_home_link().'" title="UAMS Health"><span class="fas fa-home"></span><span itemprop="name" class="sr-only">UAMS Health</span></a><meta itemprop="position" content="1">';
		} elseif ( ('inside' == uams_get_site_info()['site'] && is_front_page() ) ) {
			$crumb = '<a href="'.uams_get_home_link().'" title="Inside UAMS"><span class="fas fa-home"></span><span itemprop="name" class="sr-only">University of Arkansas for Medical Sciences</span></a><meta itemprop="position" content="1">';
		} else {
			if ( is_front_page() && 'main' != uams_get_site_info()['department'] && 'none' != uams_get_site_info()['department'] ) {
				$crumb = '<a href="'.uams_get_home_link().'" title="University of Arkansas for Medical Sciences"><span class="fas fa-home"></span><span itemprop="name" class="sr-only">University of Arkansas for Medical Sciences</span></a><meta itemprop="position" content="1"></li><li class="breadcrumb-item" itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem"><a href="' . network_home_url() . '" title="' . get_blog_details(1)->blogname . '"><span itemprop="name">'.get_blog_details(1)->blogname.'</span></a><meta itemprop="position" content="2">';
			} elseif ( 'main' != uams_get_site_info()['department'] && 'none' != uams_get_site_info()['department'] ) {
				$crumb = '<a href="'.uams_get_home_link().'" title="University of Arkansas for Medical Sciences"><span class="fas fa-home"></span><span itemprop="name" class="sr-only">University of Arkansas for Medical Sciences</span></a><meta itemprop="position" content="1"></li><li class="breadcrumb-item" itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem"><a href="' . network_home_url() . '" title="' . get_blog_details(1)->blogname . '"><span itemprop="name">'.get_blog_details(1)->blogname.'</span></a><meta itemprop="position" content="2"></li><li class="breadcrumb-item" itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem"><a href="' . home_url() . '/" title="' . uams_site_title() . '"><span itemprop="name">'.uams_site_title().'</span></a><meta itemprop="position" content="3">';
			} elseif ( is_front_page() ) {
				$crumb = '<a href="'.uams_get_home_link().'" title="University of Arkansas for Medical Sciences"><span class="fas fa-home"></span><span itemprop="name" class="sr-only">University of Arkansas for Medical Sciences</span></a><meta itemprop="position" content="1">';
			} else {
				$crumb = '<a href="'.uams_get_home_link().'" title="University of Arkansas for Medical Sciences"><span class="fas fa-home"></span><span itemprop="name" class="sr-only">University of Arkansas for Medical Sciences</span></a><meta itemprop="position" content="1"></li><li class="breadcrumb-item" itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem"><a href="' . home_url() . '/" title="' . uams_site_title() . '"><span itemprop="name">'.uams_site_title().'</span></a><meta itemprop="position" content="2">';
			}
		}
     return $crumb;
}
// Add Site Title as the Breadcrumb Title for front page
add_filter ( 'genesis_page_crumb', 'uams_breadcrumb_home' );
add_filter ( 'genesis_single_crumb', 'uams_breadcrumb_home' );
function uams_breadcrumb_home( $crumb ) {
	if ( is_front_page() ) {
		$crumb = uams_site_title();
	}
	
	return $crumb;
	
}

add_filter('seopress_pro_breadcrumbs_crumbs', 'sp_pro_breadcrumbs_crumbs');
function sp_pro_breadcrumbs_crumbs($crumbs) {
	//$crumbs is a multidimensional array.
	//First array: key=position, second array: 0=>page title, 1=>URL
	//do your stuff
	// Change "Home" to site title
	$crumbs[0][0] = uams_site_title();
	if ( 'uamshealth' == uams_get_site_info()['site'] ) {
		if ( ( 'main' == uams_get_site_info()['subsite']) ) {
		$crumbs[0] = array('<span class="fas fa-home"></span><span class="sr-only">UAMS Health</span>', uams_get_home_link().'/');
		} else {
			$home = array('<span class="fas fa-home"></span><span class="sr-only">UAMS Health</span>', uams_get_home_link().'/');
			array_unshift($crumbs, $home);
		}
	} elseif ( ('inside' == uams_get_site_info()['site'] ) ) {
		if ( ( 'main' == uams_get_site_info()['subsite']) ) {
			$crumbs[0] = array('<span class="fas fa-home"></span><span class="sr-only">Inside UAMS</span>', uams_get_home_link().'/');
		} else {
			$home = array('<span class="fas fa-home"></span><span class="sr-only">Inside UAMS</span>', uams_get_home_link().'/');
			array_unshift($crumbs, $home);
		}
	} elseif ( ('institute' == uams_get_site_info()['site'] ) ) {
		if ( ( 'main' == uams_get_site_info()['department']) ) {
			$home = array('<span class="fas fa-home"></span><span class="sr-only">University of Arkansas for Medical Sciences</span>', 'https://www.uams.edu/');
			array_unshift($crumbs, $home);
			// $crumbs[0] = array('<span class="fas fa-home"></span><span class="sr-only">University of Arkansas for Medical Sciences</span>', 'https://www.uams.edu/');
		} else {
			// if ('dept' == uams_get_site_info()['department']) {
			$sitehome = array(get_blog_details(1)->blogname, network_home_url());
			array_unshift($crumbs, $sitehome);
			// }
			$home = array('<span class="fas fa-home"></span><span class="sr-only">University of Arkansas for Medical Sciences</span>', 'https://www.uams.edu/');
			array_unshift($crumbs, $home);
		}
	} else { // Site == uams

		if ( 'other' == uams_get_site_info()['subsite'] ) {
			if ( ( 'main' == uams_get_site_info()['department']) ) {
				$home = array('<span class="fas fa-home"></span><span class="sr-only">University of Arkansas for Medical Sciences</span>', 'https://www.uams.edu/');
				array_unshift($crumbs, $home);
				// $crumbs[0] = array('<span class="fas fa-home"></span><span class="sr-only">University of Arkansas for Medical Sciences</span>', 'https://www.uams.edu/');
			} else {
				// if ('dept' == uams_get_site_info()['department']) {
				$sitehome = array(get_blog_details(1)->blogname, network_home_url());
				array_unshift($crumbs, $sitehome);
				// }
				$home = array('<span class="fas fa-home"></span><span class="sr-only">University of Arkansas for Medical Sciences</span>', 'https://www.uams.edu/');
				array_unshift($crumbs, $home);
			}
		} elseif ( ( 'main' == uams_get_site_info()['subsite']) && !is_front_page() ) {
			// Set www.uams.edu as home
			$crumbs[0] = array('<span class="fas fa-home"></span><span class="sr-only">University of Arkansas for Medical Sciences</span>', 'https://www.uams.edu/');
		
		} elseif ( 'main' != uams_get_site_info()['subsite'] && 'main' != uams_get_site_info()['department'] && '' != uams_get_site_info()['department'] && 'uams' != uams_get_site_info()['department'] ) {
			// Multisite Home
			$sitehome = array(get_blog_details(1)->blogname, network_home_url());
			array_unshift($crumbs, $sitehome);
			// UAMS Home
			$home = array('<span class="fas fa-home"></span><span class="sr-only">University of Arkansas for Medical Sciences</span>', 'https://www.uams.edu/');
			array_unshift($crumbs, $home);
		} else {
			// UAMS Home
			$home = array('<span class="fas fa-home"></span><span class="sr-only">University of Arkansas for Medical Sciences</span>', 'https://www.uams.edu');
			array_unshift($crumbs, $home);
		}
	}
	// var_dump(uams_get_site_info());
	// var_dump($crumbs);
	return $crumbs;
}
function sp_pro_breadcrumbs_css() { 
	//Disable breadcrumbs inline CSS 
	return false; 
} 
add_action('seopress_pro_breadcrumbs_css', 'sp_pro_breadcrumbs_css');
/* Disabled for latest version of SEOPress Pro > 3.8.5 Included as default in plugin
add_filter('seopress_pro_breadcrumbs_html', 'sp_pro_breadcrumbs_html');
function sp_pro_breadcrumbs_html($html) {
	//$html = <nav aria-label="breadcrumb"><ol class="breadcrumb" itemscope="" itemtype="http://schema.org/BreadcrumbList"><li class="breadcrumb-item" itemprop="itemListElement" itemscope="" itemtype="http://schema.org/ListItem"><a itemtype="http://schema.org/Thing" itemprop="item" href="https://www.seopress.org/"><span itemprop="name">Home</span></a><meta itemprop="position" content="1"></li>...
	//Remove last link
	$html = preg_replace('#^(.*)<a[^>]*?>(.*?)</a>(.*?)#im', '$1$2$3', $html);
	return $html;
}
*/

/** Returns site & subsite info **/
if ( !function_exists('uams_get_site_info')):

    function uams_get_site_info()
    {
		$site = '';
		$subsite = '';
		$department = '';
		$option_name = 'uamswp_options'; // Settings page
		$siteinfo = array();
		if ( ! class_exists( 'acf' ) ) {
			// Set base defaults if no ACF
			$siteinfo = array('site' => 'uams', 'subsite' => 'uams', 'department' => 'uams');
			return $siteinfo;
			return;
		}
		/* Replace get_field functions with get_option - Remove ACF called too early errors */
		$themestyle = get_option( 'options_uamswp_template' ); // get_field( 'uamswp_template', 'option' ); // uams, inside, health
		$themelocation = get_option( 'options_uamswp_location' ); // get_field( 'uamswp_location', 'option' ); // campus, regional
		$themeinstitute = get_option( 'options_uamswp_institute' ); // get_field( 'uamswp_institute', 'option' ); // institute name
		$regional_campus = get_option( 'options_uamswp_regional_campus' ); // get_field( 'uamswp_regional_campus', 'option' ); // regional location
		$aging_dept = get_option( 'options_uamswp_institute_aging_dept' ); // get_field( 'uamswp_institute_aging_dept', 'option' ); // Institute on Aging Departments
		$eye_dept = get_option( 'options_uamswp_institute_eye_dept' ); // get_field( 'uamswp_institute_eye_dept', 'option' ); // Eye Institute Departments
		$spine_dept = get_option( 'options_uamswp_institute_spine_dept' ); // get_field( 'uamswp_institute_spine_dept', 'option' ); // Spine & Neurosciences Institute Departments
		$digihealth_dept = get_option( 'options_uamswp_institute_digi-health_dept' ); // get_field( 'uamswp_institute_digi-health_dept', 'option' ); // Institute for Digital Health & Innovation Departments
		$pri_dept = get_option( 'options_uamswp_institute_pri_dept' ); // get_field( 'uamswp_institute_pri_dept', 'option' ); // Psychiatric Research Institute Departments
		$tri_dept = get_option( 'options_uamswp_institute_tri_dept' ); // get_field( 'uamswp_institute_tri_dept', 'option' ); // Translational Research Institute Departments
		$cancer_dept = get_option( 'options_uamswp_institute_cancer_dept' ); // get_field( 'uamswp_institute_cancer_dept', 'option' ); // Cancer Institute Departments
		$uamsorganization = get_option( 'options_uamswp_uams_subsite' ); // get_field( 'uamswp_uams_subsite', 'option' ); // college 
		$cohp_dept = get_option( 'options_uamswp_uams_cohp_dept' ); // get_field( 'uamswp_uams_cohp_dept', 'option' ); // college of health prof dept
		$com_dept = get_option( 'options_uamswp_uams_com_dept' ); // get_field( 'uamswp_uams_com_dept', 'option' ); // college of medicine dept
		$con_dept = get_option( 'options_uamswp_uams_con_dept' ); // get_field( 'uamswp_uams_con_dept', 'option' ); // college of nursing dept
		$cop_dept = get_option( 'options_uamswp_uams_cop_dept' ); // get_field( 'uamswp_uams_cop_dept', 'option' ); // college of pharmacy dept
		$coph_dept = get_option( 'options_uamswp_uams_coph_dept' ); // get_field( 'uamswp_uams_coph_dept', 'option' ); // college of public health dept
		$grad_dept = get_option( 'options_uamswp_uams_grad-school_dept' ); // get_field( 'uamswp_uams_grad-school_dept', 'option' ); // graduate school dept
		$other_dept = get_option( 'options_uamswp_uams_other_dept' ); // get_field( 'uamswp_uams_other_dept' , 'option' ); // Other (Multisite)
		$healthorganization = get_option( 'options_uamswp_uamshealth_subsite' ); // get_field( 'uamswp_uamshealth_subsite', 'option' ); // health 
		$insideorganization = get_option( 'options_uamswp_inside_subsiten' ); // get_field( 'uamswp_inside_subsite', 'option' ); // inside 
		if ('health' == $themestyle) {
			$site = 'uamshealth';
			if ('' != $healthorganization) {
				$subsite = $healthorganization;
			} else {
				$subsite = 'uams';
			}
		} elseif ('inside' == $themestyle) {
			$site = 'inside';
			if ('' != $insideorganization) {
				$subsite = $insideorganization;
			} else {
				$subsite = 'uams';
			}
		} elseif ('institute' == $themestyle) {
			$site = 'institute';
			if ('' != $themeinstitute) {
				$subsite = $themeinstitute;
				if ($subsite == 'institute_aging' && '' != $aging_dept) {
					$department = $aging_dept;
				} elseif ($subsite == 'institute_eye' && '' != $eye_dept) {
					$department = $eye_dept;
				} elseif ($subsite == 'institute_spine' && '' != $spine_dept) {
					$department = $spine_dept;
				} elseif ($subsite == 'institute_digi-health' && '' != $digihealth_dept) {
					$department = $digihealth_dept;
				} elseif ($subsite == 'institute_pri' && '' != $pri_dept) {
					$department = $pri_dept;
				} elseif ($subsite == 'institute_tri' && '' != $tri_dept) {
					$department = $tri_dept;
				} elseif ($subsite == 'institute_cancer' && '' != $cancer_dept) {
					$department = $cancer_dept;
				} else {
					$department = 'uams';
				}
			} else {
				$subsite = 'uams';
			}
		} elseif ('uams' == $themestyle) {
			$site = 'uams';
			if ('' != $themelocation){
				if ('uams' != $themelocation) {
					$subsite = $themelocation;
					if ($subsite == 'regional-campus' && '' != $regional_campus) {
						$department = $regional_campus;
					}
				} else {
					if ('' != $uamsorganization) {
						$subsite = $uamsorganization;
						if ($subsite == 'health-prof' && '' != $cohp_dept) {
							$department = $cohp_dept;
						} elseif ($subsite == 'medicine' && '' != $com_dept) {
							$department = $com_dept;
						} elseif ($subsite == 'nursing' && '' != $con_dept) {
							$department = $con_dept;
						} elseif ($subsite == 'pharmacy' && '' != $cop_dept) {
							$department = $cop_dept;
						} elseif ($subsite == 'public-health' && '' != $coph_dept) {
							$department = $coph_dept;
						} elseif ($subsite == 'grad-school' && '' != $grad_dept) {
							$department = $grad_dept;
						} elseif ($subsite == 'other' && '' != $other_dept) {
							$department = $other_dept;
						} else {
							$department = 'uams';
						}
					} else {
						$subsite = 'uams';
					}
				}
			} else {
				$subsite = 'uams';
			}
		}
		$siteinfo = array('site' => $site, 'subsite' => $subsite, 'department' => $department);
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
		if ('uamshealth' == uams_get_site_info()['site'] && 'main' == uams_get_site_info()['subsite']) {
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
			$homelink = 'https://www.uams.edu';
		} elseif ('uamshealth' == uams_get_site_info()['site']) {
			$homelink = 'https://uamshealth.com';
		} elseif ('inside' == uams_get_site_info()['site']) {
			$homelink = 'https://inside.uams.edu';
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
	if(!isset($phone[3])) { return ''; }
	// note: strip out everything but numbers 
	$phone = preg_replace('/[^0-9]/', '', $phone);
	$length = strlen($phone);
	switch($length) {
		case 7:
			return preg_replace('/([0-9]{3})([0-9]{4})/', '$1-$2', $phone);
			break;
		case 10:
			return preg_replace('/([0-9]{3})([0-9]{3})([0-9]{4})/', '($1) $2-$3', $phone);
			break;
		case 11:
			return preg_replace('/([0-9]{1})([0-9]{3})([0-9]{3})([0-9]{4})/', '($2) $3-$4', $phone); // Removed country code
			break;
		case 15:
			return preg_replace('/([0-9]{3})([0-9]{3})([0-9]{4})([0-9]{5})/', '($1) $2-$3 Ext. $4', $phone); // Removed country code
			break;
		case 16:
			return preg_replace('/([0-9]{1})([0-9]{3})([0-9]{3})([0-9]{4})([0-9]{5})/', '($2) $3-$4 Ext. $5', $phone); // Removed country code
			break;
		default:
			return $phone;
			break;
	}
}
   
function format_phone_dash($phone) {
	// note: making sure we have something
	if(!isset($phone[3])) { return ''; }
	// note: strip out everything but numbers 
	$phone = preg_replace('/[^0-9]/', '', $phone);
	$length = strlen($phone);
	switch($length) {
		case 7:
			return preg_replace('/([0-9]{3})([0-9]{4})/', '$1-$2', $phone);
			break;
		case 10:
			return preg_replace('/([0-9]{3})([0-9]{3})([0-9]{4})/', '$1-$2-$3', $phone);
			break;
		case 11:
			return preg_replace('/([0-9]{1})([0-9]{3})([0-9]{3})([0-9]{4})/', '$2-$3-$4', $phone); // Removed country code
			break;
		case 15:
			return preg_replace('/([0-9]{3})([0-9]{3})([0-9]{4})([0-9]{5})/', '$1-$2-$3,$4', $phone); // Removed country code
			break; 
		case 16:
			return preg_replace('/([0-9]{1})([0-9]{3})([0-9]{3})([0-9]{4})([0-9]{5})/', '$2-$3-$4,$5', $phone); // Removed country code
			break;
		default:
			return $phone;
			break;
	}
}

if (!function_exists('apStyleDate')) {
	function apStyleDate($date){

		$date = strftime('%l:%M %P', strtotime($date));
	
		$date = str_replace(':00', '', $date);
		$date = str_replace('m', '.m.', $date);
	
		return $date;
	
	}
}

/**
 * Return sized image.
 *
 * @param integer  $id 			// id of image
 * @param integer  $prefwidth	// Preferred Output width. Set as -1 to inherit width as native ratio of prefered height.
 * @param string   $prefheight	// Preferred Output height. Set as -1 to inherit width as native ratio of prefered width.
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
	if ($prefheight == -1) {
		$prefheight = $prefwidth / $image_ratio;
	}
	if ($prefwidth == -1) {
		$prefwidth = $prefheight * $image_ratio;
	}
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

/**
 * Return dimension for gallery image.
 * For use inside image_sizer function.
 *
 * @param string	$breakpoint	// short name of breakpoint (xxs, xs, sm, md, lg, xl, xxl)
 * @param integer 	$columns	// Number of columns displayed per row
 * @param integer 	$density	// Pixel density (1 or 2)
 * @param integer	$ratio		// Aspect ratio of the image to return height in decimal (16:9 = 1.7778). Leave as 0 if not returning height. Set to -1 if height should be set as native ratio of preferred width.
 * @return integer				// image dimension
 */
function gallery_image_dimension( $breakpoint, $columns, $density = 1, $ratio = 0 ) {
	if ( $breakpoint == 'xxs' || $breakpoint == 'xs' ) {
		$viewportwidth = 768;
		$modulepadding = 32;
	} elseif ( $breakpoint == 'sm' ) {
		$viewportwidth = 992;
		$modulepadding = 48;
	} elseif ( $breakpoint == 'md' ) {
		$viewportwidth = 1200;
		$modulepadding = 48;
	} elseif ( $breakpoint == 'lg' ) {
		$viewportwidth = 1500;
		$modulepadding = 48;
	} elseif ( $breakpoint == 'xl' ) {
		$viewportwidth = 1921;
		$modulepadding = 48;
	} else {
		$viewportwidth = 2560;
		$modulepadding = 48;
	}
	if ( $ratio == -1 ) {
		$dimension = -1;
	} else {
		$dimension = ( $viewportwidth - (2 * $modulepadding) - (($columns - 1) * 30) ) / $columns;

		if ( $ratio != 0 ) {
			$dimension = $dimension / $ratio;
		}

		if ( $density > 1 ) {
			$dimension = $dimension * 2;
		}
	}
	
	return $dimension;
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

add_filter('relevanssi_modify_wp_query', 'rlv_search_all_blogs');
function rlv_search_all_blogs($query) {
	$raw_blog_list = get_sites(array('number' => 2000));
	$blog_list = array();
	foreach ($raw_blog_list as $blog) {
		$blog_list[] = $blog->blog_id;
	}
	$blog_list = implode(',', $blog_list);
	$query->set('searchblogs', $blog_list);
	return $query;
}

// Add Google Tag Manager code in <head>
add_action( 'wp_head', 'uamswp_gtm_1' );
function uamswp_gtm_1() {
	$gtm = get_option( 'options_google_tag_manager_id' );
	$gtm_disable = get_option( 'options_google_tag_manager_disable' );
	$gtmvalue = (!empty($gtm) ? $gtm : 'GTM-NGG4P7F' );
	if ($gtm_disable !== '1') {
	?>
	<!-- Google Tag Manager -->
	<script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
	new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
	j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
	'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
	})(window,document,'script','dataLayer','<?php echo $gtmvalue; ?>');</script>
	<!-- End Google Tag Manager -->
<?php } else {
	echo '<!-- Google Tag Manager is disabled -->';
}
 	}
// Add Google Tag Manager code immediately below opening <body> tag
add_action( 'genesis_before', 'uamswp_gtm_2' );
function uamswp_gtm_2( ) { 
	$gtm = get_option( 'options_google_tag_manager_id' );
	$gtm_disable = get_option( 'options_google_tag_manager_disable' );
	$gtmvalue = (!empty($gtm) ? $gtm : 'GTM-NGG4P7F' );
	if ($gtm_disable !== '1') {
	?>
	<!-- Google Tag Manager (noscript) -->
	<noscript><iframe src="https://www.googletagmanager.com/ns.html?id=<?php echo $gtmvalue; ?>"
	height="0" width="0" aria-hidden="true" style="display:none;visibility:hidden"></iframe></noscript>
	<!-- End Google Tag Manager (noscript) -->
<?php } }

add_filter( 'big_image_size_threshold', '__return_false' );

function uamswp_list_child_posts( $posttype, $posttitle ) {
	if (!isset($posttype)) {
		$posttype = 'page'; // What post_type
	}
	if (!isset($posttitle)) {
		$posttitle = 'Subpages'; // Title for the section
	}
	$page_id = get_the_ID();
	$args =  array(
		'post_type' => $posttype,
		'post_status' => 'publish',
		'post_parent' => $page_id,
		'order' => 'ASC',
		'orderby' => 'menu_order title',
		'posts_per_page' => -1, // We do not want to limit the post count
		'meta_query' => array(
			array(
				'key' => 'page_hide_from_menu',
				'value' => '1',
				'compare' => '!=',
			)
		),
	);
	$pages = New WP_Query ( $args );
	if ( $pages->have_posts() ) { ?>
		<section class="uams-module link-list link-list-layout-split bg-auto" aria-label="List of subpages under the current page">
			<div class="container-fluid">
				<div class="row">
					<div class="col-12 col-md-6 heading">
						<div class="text-container">
							<h2 class="module-title"><span class="title"><?php echo $posttitle; ?></span></h2>
						</div>
            		</div>
            		<div class="col-12 col-md-6 list">
						<ul>
						<?php
						while ( $pages->have_posts() ) : $pages->the_post();
							echo '<li class="item"><div class="text-container"><span class="h5"><a href="'.get_permalink().'">';
							echo get_the_title();
							echo '</a></span></div></li>';
						endwhile;
						wp_reset_postdata(); ?>
						</ul>
					</div>
				</div>
			</div>
		</section>
	<?php
	}
}
// Whitelist specific blocks for the Marketing Landing Page template
add_filter('allowed_block_types', function($block_types, $post) {
	$allowed_marketing = [
		'acf/action-bar',
		'acf/call-out',
		'acf/cta',
		//'acf/hero',
		'acf/link-list',
		'acf/uams-news',
		'acf/text-overlay',
		'acf/image-side',
		'acf/text-stacked',
		'acf/livewhale-calendar',
		'acf/uams-gallery',
		'acf/uams-content',
		'acf/fad-providers',
		'acf/fad-locations',
		'acf/logo-list'
	];
	if (get_page_template_slug( $post ) == 'templates/marketing.php') {
		return $allowed_marketing;
	}
	return $block_types;
}, 10, 2);
// ACF for GEO
// add_filter('acf/load_field/name=geo_valid', 'uamswp_set_geo');
add_filter('acf/prepare_field/name=geo_valid', 'uamswp_set_geo');
add_filter('acf/update_value/name=geo_valid', 'uamswp_force_geo', 10, 3);

function uamswp_force_geo($value, $post_id, $field)
{
	if (function_exists('geoip_detect2_get_external_ip_adress')) {
		$value = 'true';
	} else {
		$value = 'false';
	}
    return $value;
}
function uamswp_set_geo($field)
{
	if (function_exists('geoip_detect2_get_external_ip_adress')) {
		$value = 'true';
	} else {
		$value = 'false';
	}
	if (array_key_exists('value', $field)) {
        $field['value'] = $value;
    }
    return $field;
}

// Password reset to include /uams-login/ 
add_filter( 'retrieve_password_message', 'my_retrieve_password_message', 10, 4 );
function my_retrieve_password_message( $message, $key, $user_login, $user_data ) {
    // Start with the default content.
    $site_name = wp_specialchars_decode( get_option( 'blogname' ), ENT_QUOTES );
    $message = __( 'Someone has requested a password reset for the following account:' ) . "\r\n\r\n";
    /* translators: %s: site name */
    $message .= sprintf( __( 'Site Name: %s' ), $site_name ) . "\r\n\r\n";
    /* translators: %s: user login */
    $message .= sprintf( __( 'Username: %s' ), $user_login ) . "\r\n\r\n";
    $message .= __( 'If this was a mistake, just ignore this email and nothing will happen.' ) . "\r\n\r\n";
    $message .= __( 'To reset your password, visit the following address:' ) . "\r\n\r\n";
    $message .= '<' . network_site_url( "/uams-login/?action=rp&key=$key&login=" . rawurlencode( $user_login ), 'login' ) . ">\r\n";
    /*
     * If the problem persists with this filter, remove
     * the last line above and use the line below by
     * removing "//" (which comments it out) and hard
     * coding the domain to your site, thus avoiding
     * the network_site_url() function.
     */
    // $message .= '<http://yoursite.com/wp-login.php?action=rp&key=' . $key . '&login=' . rawurlencode( $user_login ) . ">\r\n";
    // Return the filtered message.
    return $message;
}