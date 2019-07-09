<?php
/*
 *
 * Page options
 * 
 */

function page_options() {
    $id = get_the_id();
    if ( get_field('page_title_options', $id) ) {
        $pageTitle = get_field('page_title_options', $id);
        if ('hidden' == $pageTitle) {
            // Hide Entry-header
            add_filter('genesis_attr_entry-header', 'uamswp_attributes_entry_header');
        } elseif ('graphic' == $pageTitle) {
            // Graphic Header
            // Romve original
            remove_action( 'genesis_entry_header', 'genesis_entry_header_markup_open', 5 );
            remove_action( 'genesis_entry_header', 'genesis_do_post_title' );
            remove_action( 'genesis_entry_header', 'genesis_entry_header_markup_close', 15 );
            // Add new location
            add_action( 'genesis_before_content', 'genesis_entry_header_markup_open', 5 );
            add_action( 'genesis_before_content', 'genesis_do_post_title' );
            add_action( 'genesis_before_content', 'genesis_entry_header_markup_close', 15 );

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
        }
    }
}

function uamswp_attributes_entry_header($attributes)
{
	$attributes['class'] .= ' sr-only';
	
	return $attributes;
}

function uamswp_page_hero() {
    $hero_rows = get_field('page_hero')['hero'];

    include( get_stylesheet_directory() .'/blocks/hero.php' );
}