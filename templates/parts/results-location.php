<?php
/**
 * Location archive partial
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
 * Get the post title (relies on global $post if not explicitly passed)
 */
$post_title = get_the_title( );

/**
 * Get the permalink for the post
 */
$post_link = get_the_permalink();

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
 * Generate excerpt specifically for location custom post type using ACF fields
 */
if ( empty($excerpt) ) {
    /* Make a custom excerpt */
    // First, try to get the 'location_short_desc' field and trim if available
    $excerpt = get_field('location_short_desc');
    if ($excerpt) {
        $excerpt = wp_trim_words( $excerpt, 20, '... ' );
    }
    
    // If still empty, get the 'location_about' field, strip tags, and trim
    $about_loc = get_field('location_about');
    if (empty($excerpt)){
        if ($about_loc){
            $excerpt = wp_trim_words(wp_strip_all_tags($about_loc), 20, '...');
        }
    }
}
?>

<!-- Display the location post as a summary article with Bootstrap classes -->
<article class="post-summary type-post entry pb-4">
    <!-- Post title as a heading with link to full post -->
    <h3 class="h4"><a href="<?php echo $post_link; ?>"><?php echo $post_title; ?></a></h3>
    
    <!-- Row for thumbnail/icon and excerpt content -->
    <div class="row">
        <!-- Column for thumbnail or default icon (2/12 width) -->
        <div class="col-2">
        <?php if ($post_thumb) {
                // If thumbnail exists, output it
                echo $post_thumb;
            } else { ?>
                <!-- Fallback: Map icon as a base64-encoded PNG image (note: may be a generic icon for locations) -->
                <img src="data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMTAwIiBoZWlnaHQ9IjEwMCIgdmlld0JveD0iMCAwIDI0IDI0IiBmaWxsPSJub25lIiB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciPgo8cGF0aCBkPSJNMTUgMTAuNUMxNSAxMi4xNTY5IDEzLjY1NjkgMTMuNSAxMiAxMy41QzEwLjM0MzEgMTMuNSA5IDEyLjE1NjkgOSAxMC41QzkgOC44NDMxNSAxMC4zNDMxIDcuNSAxMiA3LjVDMTMuNjU2OSA3LjUgMTUgOC44NDMxNSAxNSAxMC41WiIgc3Ryb2tlPSIjQTlBN0E4IiBzdHJva2Utd2lkdGg9IjEuNSIgc3Ryb2tlLWxpbmVjYXA9InJvdW5kIiBzdHJva2UtbGluZWpvaW49InJvdW5kIi8+CjxwYXRoIGQ9Ik0xOS41IDEwLjVDMTkuNSAxNy42NDIxIDEyIDIxLjc1IDEyIDIxLjc1QzEyIDIxLjc1IDQuNSAxNy42NDIxIDQuNSAxMC41QzQuNSA2LjM1Nzg2IDcuODU3ODYgMyAxMiAzQzE2LjE0MjEgMyAxOS41IDYuMzU3ODYgMTkuNSAxMC41WiIgc3Ryb2tlPSIjQTlBN0E4IiBzdHJva2Utd2lkdGg9IjEuNSIgc3Ryb2tlLWxpbmVjYXA9InJvdW5kIiBzdHJva2UtbGluZWpvaW49InJvdW5kIi8+Cjwvc3ZnPgo=" alt="Location Icon - No image available"/>
        <?php } ?>
        <!-- Label indicating the post type -->
        <div class="search-type"><?php echo $post_type_name; ?></div>
        </div>
        <!-- Main content column (10/12 width) -->
        <div class="col-10">
            <!-- Display the excerpt with a "Continue Reading" link appended -->
            <div class="search-excerpt"><?php echo $excerpt . (str_word_count($excerpt) >= 20 || empty($excerpt) ? ' <a href="'.$post_link.'">View Location</a>' : ''); ?></div>
        </div>
    </div>
</article>
