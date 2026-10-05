<?php
/**
 * Template Name: Posts Home
 */

add_filter( 'genesis_post_title_output', 'uamswp_post_title_link' );
function uamswp_post_title_link( $title ): string {
	return '<h2 class="module-title" itemprop="headline"><span class="title">' . get_the_title() . '</span></h2>';
}

add_filter( 'get_the_excerpt', 'uamswp_link_excerpt_more' );
function uamswp_link_excerpt_more( $output ): string {
    $more = sprintf( '</p><p><a href="%1$s" class="btn btn-outline-primary" rel="bookmark" aria-label="%2$s">%3$s</a>', esc_url( get_the_permalink() ), esc_attr( get_the_title() ), esc_html__( 'Read More' ) );
    return $output . $more;
}

genesis(); 