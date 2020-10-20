<?php
/**
 * Template Name: Marketing Landing Page
 *
 * @package      UAMSWP
 * @author       Bill Erickson
 * @since        1.0.0
 * @license      GPL-2.0+
**/
// add_filter( 'genesis_pre_get_option_site_layout', '__genesis_return_full_width_content' );
// remove_action( 'genesis_sidebar', 'genesis_do_sidebar' );

// function uamswp_modules_display()
// {	
// 	uamswp_modules();
// }
// remove_action( 'genesis_entry_content', 'genesis_do_post_content' );
// add_action( 'genesis_entry_content', 'uamswp_modules_display' );

// add_filter( 'body_class', 'uamswp_marketing_body_class' );
// function uamswp_marketing_body_class( $classes ) {
//     $classes[] = 'page-template-default';
//     return $classes;
// }

remove_action( 'genesis_entry_footer', 'genesis_post_meta' );

// Remove Primary Nav
remove_action( 'genesis_after_header', 'genesis_do_nav' );

// Remove breadcrumbs
remove_action( 'genesis_after_header', 'genesis_do_breadcrumbs' );
remove_action( 'genesis_after_header', 'sp_breadcrumb_after_header' );

genesis();