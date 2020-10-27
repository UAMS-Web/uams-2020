<?php
/**
 * Template Name: Posts Home
 */

add_filter( 'genesis_post_title_output', 'uamswp_post_title_link' );
function uamswp_post_title_link( $title ) {
	$title = '<h2 class="module-title" itemprop="headline"><span class="title">' . get_the_title() . '</span></h2>';
	return $title;
}

add_filter( 'get_the_excerpt', 'uamswp_link_excerpt_more' );
function uamswp_link_excerpt_more( $output ) {
    $more = sprintf( '</p><p><a href="%s" class="btn btn-outline-primary" rel="bookmark" aria-label="' . get_the_title() . '">' . __( 'Read More' ) . '</a>', esc_url( get_the_permalink() ) );
    return $output . $more;
}

genesis(); 