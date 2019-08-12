<?php
/**
 * Template Name: Modules
 *
 * @package      UAMSWP
 * @author       Bill Erickson
 * @since        1.0.0
 * @license      GPL-2.0+
**/
add_filter( 'genesis_pre_get_option_site_layout', '__genesis_return_full_width_content' );
remove_action( 'genesis_sidebar', 'genesis_do_sidebar' );

function uamswp_modules_display()
{	
	uamswp_modules();
}
remove_action( 'genesis_entry_content', 'genesis_do_post_content' );
add_action( 'genesis_entry_content', 'uamswp_modules_display' );


// Build the page // Original BE Code
// get_header();
// module_page_options();
// echo '<div class="content-sidebar-wrap row"><main class="content col-sm-12" id="genesis-content">';
// echo '<article class="page type-page modules-layout" itemscope itemtype="https://schema.org/CreativeWork">';
// uamswp_modules();
// echo '</article>';
// echo '</main></div>';
// get_footer();

genesis();