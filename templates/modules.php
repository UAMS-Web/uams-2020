<?php
/**
 * Template Name: Modules
 *
 * @package      UAMSWP
 * @author       Bill Erickson
 * @since        1.0.0
 * @license      GPL-2.0+
**/
// Remove 'site-inner' from structural wrap
add_theme_support( 'genesis-structural-wraps', array( 'header', 'footer-widgets', 'footer' ) );
/**
 * Add the attributes from 'entry', since this replaces the main entry
 *
 * @author Bill Erickson
 * @link http://www.billerickson.net/full-width-landing-pages-in-genesis/
 *
 * @param array $attributes Existing attributes.
 * @return array Amended attributes.
 */
function be_site_inner_attr( $attributes ) {
	// Add a class of 'full' for styling this .site-inner differently
	$attributes['class'] .= ' full';
	// Add an id of 'genesis-content' for accessible skip links
	$attributes['id'] = 'genesis-content';
	// Add the attributes from .entry, since this replaces the main entry
	$attributes = wp_parse_args( $attributes, genesis_attributes_entry( array() ) );
	return $attributes;
}
add_filter( 'genesis_attr_site-inner', 'be_site_inner_attr' );

$side_fields = require( get_stylesheet_directory() .'/acf_fields/image-side-by-side.php' );
// Build the page
get_header();
uamswp_modules();
get_footer();