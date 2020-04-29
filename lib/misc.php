<?php
/**
 * Miscellaneous
 *
 * @package      Bootstrap for Genesis
 * @since        1.0
 * @link         http://webdevsuperfast.github.io
 * @author       Rotsen Mark Acob <webdevsuperfast.github.io>
 * @copyright    Copyright (c) 2015, Rotsen Mark Acob
 * @license      http://opensource.org/licenses/gpl-2.0.php GNU Public License
 *
*/

// Custom Image Function
function uamswp_post_image() {
	global $post;
	$image = '';
	$image_id = get_post_thumbnail_id( $post->ID );
	$image = wp_get_attachment_image_src( $image_id, 'full' );
	$image = $image[0];
	if ( $image ) return $image;
	return uamswp_get_first_image();
}

// Get the First Image Attachment Function
function uamswp_get_first_image() {
	global $post, $posts;
	$first_img = '';
	ob_start();
	ob_end_clean();
	$output = preg_match_all( '/<img.+src=[\'"]([^\'"]+)[\'"].*>/i', $post->post_content, $matches );
	$first_img = "";
	if ( isset( $matches[1][0] ) )
		$first_img = $matches[1][0];
	return $first_img;
}

// Custom Meta
add_action( 'genesis_meta', 'uamswp_do_meta' );
function uamswp_do_meta() {
	// Jumbotron
	if ( is_front_page() && is_active_sidebar( 'home-featured' ) ) add_action( 'genesis_after_header', 'uamswp_do_home_featured' );

	// Body Class
	add_filter( 'body_class', 'uamswp_body_class' );
}

// Jumbotron
// function uamswp_do_home_featured() {
// 	genesis_markup( array(
// 		'open' => '<div %s>',
// 		'context' => 'home-featured'
// 	) );

// 	genesis_structural_wrap( 'home-featured' );

// 	genesis_widget_area( 'home-featured', array(
// 		'before' => '',
// 		'after' => ''
// 	) );

// 	genesis_structural_wrap( 'home-featured', 'close' );

// 	genesis_markup( array(
// 		'close' => '</div>',
// 		'context' => 'home-featured'
// 	) );
// }

// Body Class
function uamswp_body_class( $args ) {
	if ( is_page_template( 'page_blog.php' ) )
		$args[] = 'blog';

	return $args;
}

/* Add site & subsite to body class - used for site customizations (ex. different background image in footer) */
add_filter( 'genesis_attr_body', 'uamswp_site_add_css_attr' );
function uamswp_site_add_css_attr( $attributes ) {
    // Add the site & subsite to  class ex. uams medicine, institute cancer
    $attributes['class'] = $attributes['class'] . ' '. uams_get_site_info()['site'] . ' '. uams_get_site_info()['subsite'];
    return $attributes;
}

// Remove site-inner markup
add_filter( 'genesis_markup_site-inner', '__return_null' );

// Remove content/sidebar layout.
// genesis_unregister_layout( 'content-sidebar' );
 
// Remove sidebar/content layout.
genesis_unregister_layout( 'sidebar-content' );
 
// Remove content/sidebar/sidebar layout.
genesis_unregister_layout( 'content-sidebar-sidebar' );
 
// Remove sidebar/sidebar/content layout.
genesis_unregister_layout( 'sidebar-sidebar-content' );
 
// Remove sidebar/content/sidebar layout.
genesis_unregister_layout( 'sidebar-content-sidebar' );

// Set full-width content as the default layout.
genesis_set_default_layout( 'full-width-content' );

//* Force full-width-content layout setting
add_filter( 'genesis_pre_get_option_site_layout', '__genesis_return_full_width_content' );
//* Remove Genesis Layout Settings
// remove_theme_support( 'genesis-inpost-layouts' );

// Remove gutenberg blocks
// add_filter( 'allowed_block_types', 'uamswp_allowed_block_types' );
 
function uamswp_allowed_block_types( $allowed_blocks ) {

	// get widget blocks and registered by plugins blocks
	$registered_blocks = WP_Block_Type_Registry::get_instance()->get_all_registered();
 
	// in case we do not need widgets
	unset( $registered_blocks[ 'core/latest-comments' ] );
	unset( $registered_blocks[ 'core/archives' ] );
	unset( $registered_blocks[ 'core/tag-cloud' ] );
	unset( $registered_blocks[ 'core/search' ] );
	unset( $registered_blocks[ 'core/rss' ] );
	unset( $registered_blocks[ 'core/calendar' ] );
	unset( $registered_blocks[ 'core/latest-posts' ] );
	unset( $registered_blocks[ 'acf/acfb-starrating' ] );
	unset( $registered_blocks[ 'acf/acfb-pricelist' ] );
	unset( $registered_blocks[ 'acf/acfb-pricingbox' ] );
	unset( $registered_blocks[ 'acf/acfb-multibuttons' ] );
	unset( $registered_blocks[ 'acf/acfb-progressbar' ] );
	// unset( $registered_blocks[ 'acf/acfb-pricingbox' ] );
 
	// now $registered_blocks contains only blocks registered by plugins, but we need keys only
	$registered_blocks = array_keys( $registered_blocks );
 
	// merge the whitelist with plugins blocks
	return array_merge( array(
		// Common
		'core/image',
		'core/paragraph',
		'core/heading',
		'core/list',
		'core/quote',
		'core/file',
		'core/gallery',
		// 'core/video', // Use embeds??
		// Formatting
		'core/table',
		'core/code',
		'core/freeform', // Classic
		'core/html', // Custom HTML
		'core/preformatted',
		'core/pullquote',
		// Layout
		'core/media-text',
		'core/spacer',
		'core/separator', // HR
		// Embeds
		'core/embed',
		'core-embed/twitter',
		'core-embed/youtube',
		'core-embed/facebook',
		'core-embed/instagram',
		'core-embed/flickr',
		'core-embed/vimeo',
		'core-embed/issuu',
		'core-embed/screencast',
		'core-embed/scribd',
		'core-embed/slideshare',
		'core-embed/ted',
	), $registered_blocks );
 
}

/**
 * Disable Editor
 *
 * @package      ClientName
 * @author       Bill Erickson
 * @since        1.0.0
 * @license      GPL-2.0+
**/

/**
 * Templates and Page IDs without editor
 *
 */
function uamswp_disable_editor( $id = false ) {

	$excluded_templates = array(
		'templates/modules.php',
		// 'templates/contact.php'
	);

	$excluded_ids = array(
		// get_option( 'page_on_front' )
	);

	if( empty( $id ) )
		return false;

	$id = intval( $id );
	$template = get_page_template_slug( $id );

	return in_array( $id, $excluded_ids ) || in_array( $template, $excluded_templates );
}

/**
 * Disable Gutenberg by template
 *
 */
function uamswp_disable_gutenberg( $can_edit, $post_type ) {

	if( ! ( is_admin() && !empty( $_GET['post'] ) ) )
		return $can_edit;

	if( uamswp_disable_editor( $_GET['post'] ) )
		$can_edit = false;

	return $can_edit;

}
add_filter( 'gutenberg_can_edit_post_type', 'uamswp_disable_gutenberg', 10, 2 );
add_filter( 'use_block_editor_for_post_type', 'uamswp_disable_gutenberg', 10, 2 );

/**
 * Disable Classic Editor by template
 *
 */
function uamswp_disable_classic_editor() {

	$screen = get_current_screen();
	if( 'page' !== $screen->id || ! isset( $_GET['post']) )
		return;

	if( uamswp_disable_editor( $_GET['post'] ) ) {
		remove_post_type_support( 'page', 'editor' );
	}

}
add_action( 'admin_head', 'uamswp_disable_classic_editor' );