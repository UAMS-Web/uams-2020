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
function uamswp_do_home_featured() {
	genesis_markup( array(
		'open' => '<div %s>',
		'context' => 'home-featured'
	) );

	genesis_structural_wrap( 'home-featured' );

	genesis_widget_area( 'home-featured', array(
		'before' => '',
		'after' => ''
	) );

	genesis_structural_wrap( 'home-featured', 'close' );

	genesis_markup( array(
		'close' => '</div>',
		'context' => 'home-featured'
	) );
}

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
remove_theme_support( 'genesis-inpost-layouts' );