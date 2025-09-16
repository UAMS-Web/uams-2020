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
 * Get the post title (relies on global $post if not explicitly passed)
 */
$post_title = get_the_title( );

/**
 * Get the permalink for the post
 */
$post_link = get_permalink( );

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
?>

<!-- Display the post as a summary article with Bootstrap classes -->
<article class="post-summary type-post entry entry pb-4">
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
                <!-- Fallback: Generic icon as a base64-encoded PNG image -->
                <img src="data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMTAwIiBoZWlnaHQ9IjEwMCIgdmlld0JveD0iMCAwIDI0IDI0IiBmaWxsPSJub25lIiB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciPgo8cGF0aCBkPSJNMTkuNSAxNC4yNVYxMS42MjVDMTkuNSA5Ljc2MTA0IDE3Ljk4OSA4LjI1IDE2LjEyNSA4LjI1SDE0LjYyNUMxNC4wMDM3IDguMjUgMTMuNSA3Ljc0NjMyIDEzLjUgNy4xMjVWNS42MjVDMTMuNSAzLjc2MTA0IDExLjk4OSAyLjI1IDEwLjEyNSAyLjI1SDguMjVNMTAuNSAyLjI1SDUuNjI1QzUuMDAzNjggMi4yNSA0LjUgMi43NTM2OCA0LjUgMy4zNzVWMjAuNjI1QzQuNSAyMS4yNDYzIDUuMDAzNjggMjEuNzUgNS42MjUgMjEuNzVIMTguMzc1QzE4Ljk5NjMgMjEuNzUgMTkuNSAyMS4yNDYzIDE5LjUgMjAuNjI1VjExLjI1QzE5LjUgNi4yNzk0NCAxNS40NzA2IDIuMjUgMTAuNSAyLjI1WiIgc3Ryb2tlPSIjQTlBN0E4IiBzdHJva2Utd2lkdGg9IjEuNSIgc3Ryb2tlLWxpbmVjYXA9InJvdW5kIiBzdHJva2UtbGluZWpvaW49InJvdW5kIi8+Cjwvc3ZnPgo=" alt="No preview available"/>
        <?php } ?>
        <!-- Label indicating the post type -->
        <div class="search-type"><?php echo $post_type_name; ?></div>
        </div>
        <!-- Main content column (10/12 width) -->
        <div class="col-10">
            <!-- Display the excerpt -->
            <div class="search-excerpt"><?php echo $excerpt; ?></div>
        </div>
    </div>
</article>
