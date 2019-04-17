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

//Does this need to move to nav.php and tie into function uamswp_navbar_brand_markup?
// Here goes the logo in Header -- need to update this with SVG magic?
add_action( 'genesis_header', 'ursidae_site_image', 5 );
 
function ursidae_site_image() {
   	$header_image = '<img src="' . get_stylesheet_directory_uri() .'/images/logo.png" width="300" alt="University of Arkansas for Medical Sciences Logo" />';
   	//image width set for quick mock up. needs correct logo.png or SVG
	printf( '<div class="float-left"><a href="http://uams.edu/">%s</a> <span class="pipe" style="color:white"> | </span> <a class="navbar-brand" title="'.esc_attr( get_bloginfo( 'description' ) ).'" href="'.esc_url( home_url( '/' ) ).'">'.get_bloginfo( 'name' ).'</a></div>', $header_image );
	printf( '<div class="float-right">The Stuff That Floats Right Goes Here</div>');

	//font color set for mock up. update CSS
}