<?php
/**
 * Fallback archive partial
 *
 * @package      uams-2020
 * @author       Todd McKee
 * @since        1.0.0
 * @license      GPL-2.0+
 */

/**
 * Retrieve post and blog IDs from the data array provided by the get_template_part function
 */
$post_id = $data['post_id'];
$blog_id = $data['blog_id'];

/**
 * Fetch the post object using the provided post ID
 */
$post = get_post( $post_id );

/**
 * Get the post title
 */
$post_title = get_the_title( $post_id );

/**
 * Get the permalink for the post
 */
$post_link = get_permalink( $post_id );

/**
 * Determine the post type and retrieve its singular name label
 */
$post_type = $post->post_type;
$post_type_name = get_post_type_object($post_type)->labels->singular_name;

/**
 * Initialize thumbnail variable as empty string
 */
$post_thumb = '';

/**
 * Check if the post has a featured image/thumbnail
 */
if(has_post_thumbnail( $post_id )) {
    // If yes, retrieve the HTML for the thumbnail image
    $post_thumb = get_the_post_thumbnail( $post_id );
}

/**
 * Initialize excerpt variable as empty string
 */
$excerpt = '';

/**
 * Check if the post has a manually set excerpt
 */
if ( has_excerpt( $post_id ) ) {
    // If yes, use the manual excerpt
    $excerpt = get_the_excerpt( $post_id );
} else {
    // Otherwise, generate an automatic excerpt from the post content
    $excerpt = wp_trim_excerpt( "", $post_id );
}

/**
 * Prefer UAMS Search's highlighted excerpt (escaped; only <mark> added).
 * The loop has already switched to the result's site.
 */
if ( function_exists( 'uams_search_excerpt' ) ) {
    $uams_excerpt = uams_search_excerpt( (int) $post_id, get_current_blog_id() );
    if ( '' !== $uams_excerpt ) {
        $excerpt = $uams_excerpt;
    }
}

/**
 * Shared result markup: breadcrumb, title, excerpt, image when the post has one.
 * See uamswp_search_render_result() in lib/search.php.
 */
uamswp_search_render_result( array(
    'post_id' => (int) $post_id,
    'title'   => $post_title,
    'url'     => $post_link,
    'excerpt' => $excerpt,
    'meta'    => '',
) );
