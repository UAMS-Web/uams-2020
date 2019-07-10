<?php
/*
 *
 * Page options
 * 
 */

add_action( 'genesis_after_header', 'page_options', 5 );
function page_options() {
    $id = get_the_id();
    if ( get_field('page_title_options', $id) ) {
        $pageTitle = get_field('page_title_options', $id);
        if ('hidden' == $pageTitle) {
            // Hide Entry-header
            add_filter('genesis_attr_entry-header', 'uamswp_attributes_entry_header');
        } elseif ('graphic' == $pageTitle) {
            // Graphic Header
            // Remove original
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
    $id = 'header';
    $hero_rows = get_field('page_hero')['hero'];
    echo '<div class="col-12">';
    include( get_stylesheet_directory() .'/blocks/hero.php' );
    echo '</div>';
}

/**
 * Check if page settings say breadcrumbs should be hidden.
 *
 * @return string $id or false
 *
 * @since 1.0
 * @author Josh Daugherty
 */
function uamswp_hide_breadcrumbs(){
    $hidebreadcrumbs = false;
    if (get_post_meta( get_the_id(), 'page_hide_breadcrumbs', true)) {
        $id = get_the_id();
        $hidebreadcrumbs = true;
    }
    if ($hidebreadcrumbs) {
        return $id;
    } else {
        return false;
    }
}

/**
 * 
 * Remove Breadcrumbs if page settings say breadcrumbs should be hidden.
 * 
 * @since 1.0
 * @author Josh Daugherty
 */
add_action( 'template_redirect', 'remove_breadcrumbs' );
function remove_breadcrumbs() {
	if ( uamswp_hide_breadcrumbs() ) {
		remove_action( 'genesis_after_header', 'genesis_do_breadcrumbs' );
	}
}