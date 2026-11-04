<?php
/**
 * Page archive partial
 *
 * @package      uams-2020
 * @author       Todd McKee
 * @since        1.0.0
 * @license      GPL-2.0+
 */

/**
 * Retrieve post and blog IDs from the data array provided by the template_part function
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
 * Initialize thumbnail variable and check for featured image
 */
if(has_post_thumbnail( $post_id )) {
    // If a thumbnail exists, get its URL with 'thumbnail' size
    $post_thumb = get_the_post_thumbnail_url( $post->ID, 'thumbnail' );
} else {
    // Attempt to get the thumbnail image source; fallback to empty string if none exists
    $post_thumb = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), 'thumbnail' ) ?? '';
}

/**
 * Initialize excerpt variable
 */
$excerpt = '';

/**
 * Check if the post has a manually set excerpt
 */
if ( has_excerpt( $post_id ) ) {
    // Use the manual excerpt if available
    $excerpt = get_the_excerpt( $post_id );
} else {
    // Generate an automatic excerpt from the post content
    $excerpt = wp_trim_excerpt( "", $post_id );
}

/**
 * If no excerpt was found, create a custom one
 */
if ( empty($excerpt) ) {
    // Remove shortcodes from the post content
    $text = strip_shortcodes( $post->post_content );
    // Apply content filters (e.g., formatting, paragraphs)
    $text = apply_filters( 'the_content', $text );
    // Escape CDATA closing tags to prevent XML issues
    $text = str_replace(']]>', ']]&gt;', $text);
    // Trim to 20 words and append ellipsis
    $excerpt = wp_trim_words( $text, 20, '... ' );
}

/**
 * Commented-out debug line for post and blog IDs
 */
// echo 'Post ID: ' . $data['post_id'] . ' Blog ID: ' . $data['blog_id'];

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
