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
function remove_page_from_query_string($query_string) {
    if (isset($query_string['name'] ) ) {
        if ($query_string['name'] == 'page' && isset($query_string['page'])) {
            unset($query_string['name']);
            $query_string['paged'] = $query_string['page'];
        }
    }
	return $query_string;
}
add_filter('request', 'remove_page_from_query_string');