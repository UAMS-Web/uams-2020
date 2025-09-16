<?php
/**
 * Post archive partial
 *
 * @package      uams-2020
 * @author       Todd McKee
 * @since        1.0.0
 * @license      GPL-2.0+
**/
/* From data from template_part function */
$post_id = $data['post_id'];
$blog_id = $data['blog_id'];

/**
 * Retrieves post data and generates a summary article for display.
 * This includes title, link, thumbnail (or default icon), date, and excerpt.
 */

// Retrieve the full post object using the provided post ID
$post = get_post( $post_id );

// Get the post title (uses global $post if $post_id not specified, but here it relies on context)
$post_title = get_the_title( );

// Get the permalink for the post
$post_link = get_the_permalink();

// Initialize thumbnail variable as empty string
$post_thumb = '';

// Check if the post has a featured image/thumbnail
if(has_post_thumbnail( $post_id )) {
    // If yes, retrieve the HTML for the thumbnail image
    $post_thumb = get_the_post_thumbnail( $post_id );
}

// Initialize excerpt variable as empty string
$excerpt = '';

// Check if the post has a manually set excerpt
if ( has_excerpt( $post_id ) ) {
    // If yes, use the manual excerpt
    $excerpt = get_the_excerpt( $post_id );
} else {
    // Otherwise, generate an automatic excerpt from the post content
    $excerpt = wp_trim_excerpt( "", $post_id );
}

// If the excerpt is still empty after the above, create a custom one
if ( empty($excerpt) ) {
    /* Make a custom excerpt */
    // Get the post content and remove shortcodes
    $text = strip_shortcodes( $post->post_content );
    // Apply content filters (e.g., for paragraphs, etc.)
    $text = apply_filters( 'the_content', $text );
    // Escape CDATA closing tags to prevent XML issues
    $text = str_replace(']]>', ']]&gt;', $text);
    // Trim to 20 words and append ellipsis
    $excerpt = wp_trim_words( $text, 20, '... ' );
} 

// Commented-out debug line for post and blog IDs
// echo 'Post ID: ' . $data['post_id'] . ' Blog ID: ' . $data['blog_id'];
?>
<!-- Display the post as a summary article with Bootstrap classes -->
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
                <!-- Fallback: News icon as a base64-encoded PNG image -->
                <img src="data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMTAwIiBoZWlnaHQ9IjEwMCIgdmlld0JveD0iMCAwIDI0IDI0IiBmaWxsPSJub25lIiB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciPgo8cGF0aCBkPSJNMTIgNy41SDEzLjVNMTIgMTAuNUgxMy41TTYgMTMuNUgxMy41TTYgMTYuNUgxMy41TTE2LjUgNy41SDE5Ljg3NUMyMC40OTYzIDcuNSAyMSA4LjAwMzY4IDIxIDguNjI1VjE4QzIxIDE5LjI0MjYgMTkuOTkyNiAyMC4yNSAxOC43NSAyMC4yNU0xNi41IDcuNVYxOEMxNi41IDE5LjI0MjYgMTcuNTA3NCAyMC4yNSAxOC43NSAyMC4yNU0xNi41IDcuNVY0Ljg3NUMxNi41IDQuMjUzNjggMTUuOTk2MyAzLjc1IDE1LjM3NSAzLjc1SDQuMTI1QzMuNTAzNjggMy43NSAzIDQuMjUzNjggMyA0Ljg3NVYxOEMzIDE5LjI0MjYgNC4wMDczNiAyMC4yNSA1LjI1IDIwLjI1SDE4Ljc1TTYgNy41SDlWMTAuNUg2VjcuNVoiIHN0cm9rZT0iI0E5QTdBOCIgc3Ryb2tlLXdpZHRoPSIxLjUiIHN0cm9rZS1saW5lY2FwPSJyb3VuZCIgc3Ryb2tlLWxpbmVqb2luPSJyb3VuZCIvPgo8L3N2Zz4K" alt="News Icon - No image available"/>
        <?php } ?>
        <!-- Label indicating the post type -->
        <div class="search-type">News</div>
        </div>
        <!-- Main content column (10/12 width) -->
        <div class="col-10">
            <!-- Date and excerpt display -->
            <div class="search-excerpt"><em><?php echo get_the_date('F j, Y', $post_id); ?></em> &ndash; <?php echo $excerpt; ?></div>
        </div>
    </div>
</article>
