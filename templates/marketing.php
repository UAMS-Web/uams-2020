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
remove_action( 'genesis_after_header', 'custom_nav_menu', 5 );

// Remove breadcrumbs
remove_action( 'genesis_after_header', 'genesis_do_breadcrumbs' );
remove_action( 'genesis_after_header', 'sp_breadcrumb_after_header' );

// Remove subpages/child menu
remove_action('genesis_after_entry', 'uamswp_list_child_pages');

// Add Page Header Options
remove_action( 'genesis_after_header', 'page_options', 5 ); // remove default template action
add_action( 'genesis_after_header', 'mlp_header_options', 5 );
function mlp_header_options() {
    $id = get_the_id();
    if ( get_field('page_title_options', $id) ) {
        $pageTitle = get_field('page_title_options', $id);
        if ('landingpage' == $pageTitle) {
            // Landing Page Header
            // Remove original
            remove_action( 'genesis_entry_header', 'genesis_entry_header_markup_open', 5 );
            remove_action( 'genesis_entry_header', 'genesis_do_post_title' );
            remove_action( 'genesis_entry_header', 'genesis_entry_header_markup_close', 15 );
            // Add new location
            add_action( 'genesis_before_content', 'uamswp_landing_page_title_wrap_open', 5 );
            add_action( 'genesis_before_content', 'genesis_entry_header_markup_open', 5 );
            add_action( 'genesis_before_content', 'uamswp_landing_page_title_inner_1', 5 );
            add_action( 'genesis_before_content', 'uamswp_landing_page_title_do_post_title' );
            add_action( 'genesis_before_content', 'uamswp_landing_page_title_inner_2', 15 );
            add_action( 'genesis_before_content', 'uamswp_landing_page_title_lead_paragraph', 15 );
            add_action( 'genesis_before_content', 'uamswp_landing_page_title_inner_3', 15 );
            add_action( 'genesis_before_content', 'genesis_entry_header_markup_close', 15 );
            add_action( 'genesis_before_content', 'uamswp_landing_page_title_wrap_close', 15 );

            // Add relevant classes
            add_filter('genesis_attr_entry-header', 'uamswp_attributes_entry_header_landing_page_title');

        } elseif ('hero' == $pageTitle) {
            // Hero
            // Remove original header
            remove_action( 'genesis_entry_header', 'genesis_entry_header_markup_open', 5 );
            remove_action( 'genesis_entry_header', 'genesis_do_post_title' );
            remove_action( 'genesis_entry_header', 'genesis_entry_header_markup_close', 15 );
            // Add new location for header
            add_action( 'genesis_before_content', 'genesis_entry_header_markup_open', 5 );
            add_action( 'genesis_before_content', 'genesis_do_post_title' );
            add_action( 'genesis_before_content', 'genesis_entry_header_markup_close', 15 );
            // Add SR-Only
            add_filter( 'genesis_attr_entry-header', 'uamswp_attributes_entry_header' );

            // Add hero section
            add_action( 'genesis_before_content', 'uamswp_page_hero', 20 );
        } else {
            add_filter('genesis_attr_entry-header', 'uamswp_attributes_entry_header');
        }
    }
}

genesis();