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
// remove_action( 'wp_head', 'genesis_custom_header_style' );
remove_action( 'genesis_header', 'genesis_do_header' );

//Does this need to move to nav.php and tie into function uamswp_navbar_brand_markup?
// Here goes the logo in Header -- need to update this with SVG magic?
add_action( 'genesis_header', 'ursidae_site_image', 5 );

 
function ursidae_site_image() {
	$header_image = '<img src="' . get_stylesheet_directory_uri() .'/assets/svg/UAMS-Logo_White.svg" alt="University of Arkansas for Medical Sciences Logo" />';

	echo '<div class="global-title">';
	printf( '<a href="https://www.uamshealth.com/" class="navbar-brand">%s<span class="sr-only">University of Arkansas for Medical Sciences</span></a>', $header_image );
	echo '<div class="navbar-subbrand">';

	// Add ancestor site name here if relevant
	//echo '<a class="parent" href="javascript:void(0)">Parent Site Name</span><span class="sr-only">:</a>';

	echo '<a class="title" title="'.esc_attr( get_bloginfo( 'description' ) ).'" href="'.esc_url( home_url( '/' ) ).'">'.get_bloginfo( 'name' ).'</a>';
	echo '</div>';
	echo '</div>';
	echo '<nav class="header-nav" aria-label="Resource Navigation">';
	echo '<div class="collapse navbar-collapse" id="nav-secondary">';
	echo '<ul class="nav">';
	echo '<li class="nav-item">';
	echo '<a class="nav-link" href="https://www.uams.edu/">UAMS.edu</a>';
	echo '</li>';
	echo '<li class="nav-item">';
	echo '<a class="nav-link" href="https://mychart.uamshealth.com/">MyChart</a>';
	echo '</li>';
	echo '<li class="nav-item">';
	echo '<a class="nav-link" href="http://giving.uams.edu/">Giving</a>';
	echo '</li>';
	echo '</ul>';
	echo '</div>';
	echo '<ul class="nav resource-nav" id="nav-resource">';
	echo '<li class="nav-item">';
	echo '<a class="nav-link" href="javascript:void(0)"><span class="fas fa-ambulance fa-lg"></span><span class="sr-only">Emergency Room</span></a>';
	echo '</li>';
	echo '<li class="nav-item">';
	echo '<a class="nav-link" href="javascript:void(0)"><span class="fas fa-search fa-lg"></span><span class="sr-only">Open Search</span></a>';
	echo '</li>';
	echo '</ul>';

	// Hiding this button until Quick Links is created.
	//echo '<button class="navbar-toggler quick-links-toggler" type="button" data-toggle="collapse" data-target="#nav-quick-links" aria-controls="quick-links" aria-expanded="false" aria-label="Toggle Quick Links navigation">';
	//echo '<span class="sr-only">Expand Quick Links</span>';
	//echo '<span class="fas fa-bars fa-lg"></span>';
	//echo '</button>';

	// The data-target and aria-controls may need to be dynamically defined.
	echo '<button class="navbar-toggler mobile-menu-toggler" type="button" data-toggle="collapse" data-target="#nav-primary" aria-controls="nav-primary" aria-expanded="false" aria-label="Toggle Primary navigation">';
	echo '<span class="sr-only">Expand Primary Nav</span>';
	echo '<span class="fas fa-bars fa-lg"></span>';
	echo '</button>';
	
	echo '</nav>';
}