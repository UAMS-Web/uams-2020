<?php
/**
 * Pagination
 *
 * @package      B4Genesis
 * @since        1.0
 * @link         http://webdevsuperfast.github.io
 * @author       Rotsen Mark Acob <webdevsuperfast.github.io>
 * @copyright    Copyright (c) 2017, Rotsen Mark Acob
 * @license      http://opensource.org/licenses/gpl-2.0.php GNU Public License
 *
*/

// Pagination Numeric
add_filter( 'genesis_prev_link_text', 'uamswp_genesis_prev_link_text_numeric' );
add_filter( 'genesis_next_link_text', 'uamswp_genesis_next_link_text_numeric' );

function uamswp_genesis_prev_link_text_numeric( $text ) {
    if ( 'numeric' === genesis_get_option( 'posts_nav' ) ) {
        return '<span aria-hidden="true">&laquo;</span>'
            . '<span class="sr-only">' . __( 'Previous Page', 'uams-2020' ) . '</span>';
    }
    return $text;
}

function uamswp_genesis_next_link_text_numeric( $text ) {
    if ( 'numeric' === genesis_get_option( 'posts_nav' ) ) {
        return '<span class="sr-only">' . __( 'Next Page', 'uams-2020' ) . '</span>'
            . '<span aria-hidden="true">&raquo;</span>';
    }
    return $text;
}
// Pagination fix for /%category%/%postname%/
function custom_pre_get_posts( $query ) {  
    if ( function_exists( 'is_multisite' ) && is_multisite() && ! is_subdomain_install() && is_main_site() ) {
        if( $query->is_main_query() && !$query->is_feed() && !is_admin() && is_category()) {  
            $query->set( 'paged', str_replace( '/', '', get_query_var( 'page' ) ) );  
        }  
    }
}
add_action('pre_get_posts','custom_pre_get_posts'); 

function custom_request($query_string ) { 
    if ( function_exists( 'is_multisite' ) && is_multisite() && ! is_subdomain_install() && is_main_site() ) {
        if( isset( $query_string['page'] ) ) { 
            if( ''!=$query_string['page'] ) { 
                if( isset( $query_string['name'] ) ) { 
                    unset( $query_string['name'] ); } 
            } 
        } 
    }
    return $query_string; 
}
add_filter('request', 'custom_request');