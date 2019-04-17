<?php
/**
 * Custom Header
 *
 * @package      Bootstrap for Genesis
 * @since        1.0
 * @link         http://webdevsuperfast.github.io
 * @author       Rotsen Mark Acob <webdevsuperfast.github.io>
 * @copyright    Copyright (c) 2015, Rotsen Mark Acob
 * @license      http://opensource.org/licenses/gpl-2.0.php GNU Public License
 *
*/

// Custom Header
remove_action( 'wp_head', 'genesis_custom_header_style' );
remove_action( 'genesis_header', 'genesis_do_header' );

// This adds logo to header, adds page title if using special page template, and...
//
// Here goes the logo in Header -- need to update this with SVG magic?
//
add_action( 'genesis_header', 'ursidae_site_image', 5 );
 
function ursidae_site_image() {
	
//if ( is_page_template( array( 'templates/page_special.php', 'page_other_special.php' ) ) ) {
	
// Do the thing below if either of the above templates are being used
//First we add the logo
	
//	$header_image = '<img src="' . get_stylesheet_directory_uri() .'/images/logo.png" alt="University of Arkansas for Medical Sciences Logo" />';
//	printf( '<div class="site-image"><a href="http://uams.edu/">%s</a> <span class="pipe">|</span></div>', $header_image );
//	printf( '<a class="navbar-brand" title="'.esc_attr( get_bloginfo( 'description' ) ).'" href="'.esc_url( home_url( '/' ) ).'">'.get_bloginfo( 'name' ).'</a>');
	
// Then start the magic to add page title in header area	
	
//	function get_post_parent( $post=null )
//	{
	//initializing
//		global $wp_the_query;
//		if (!$post)
//			$post = $wp_the_query->post;
	
	//reasons to return false
//		if (!is_object($post) || is_wp_error($post)) return false;
//		if (!$post->post_parent) return false;
	
	//load and return the parent
//		$parent = get_post($post->post_parent);
//		return $parent;
//}
/**
 * Function is responsible for returning the parent page if it exists or returning the current page if the parent does not exist.
 * Need to figure out how to make the child pages write the parent page title. This needs to happen here!
 * @return object
 */
//function get_highlighted_excerpt()
//{
//	if (!$post = get_post_parent())
//	{
//		global $post;
//	}
//	return $post;
//}
// these next lines of code should be placed in the location that you want to output the parent title and excerpt
// if the parent page exists
//if ($parent = get_highlighted_excerpt())
//{
//	echo "<div class='post-title'>{$parent->post_title}</div>";
//}
//}
//else {
	
//If no special page then drop in the logo and be done
   	$header_image = '<img src="' . get_stylesheet_directory_uri() .'/images/logo.png" width="300" alt="University of Arkansas for Medical Sciences Logo" />';
   	//image width set for quick mock up. needs correct logo.png or SVG
	printf( '<div class="site-image"><a href="http://uams.edu/">%s</a> <span class="pipe" style="color:white">|</span></div>', $header_image );
	//font color set for mock up. update CSS
//	printf( '<a class="navbar-brand" title="'.esc_attr( get_bloginfo( 'description' ) ).'" href="'.esc_url( home_url( '/' ) ).'">'.get_bloginfo( 'name' ).'</a>');	
}