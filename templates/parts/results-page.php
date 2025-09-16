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
 * Get the post title (relies on global $post if not explicitly passed)
 */
$post_title = get_the_title();

/**
 * Get the permalink for the post
 */
$post_link = get_the_permalink();

/**
 * Initialize thumbnail variable and check for featured image
 */
if(has_post_thumbnail()) {
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
?>

<!-- Display the page summary in an article with Bootstrap classes -->
<article class="post-summary type-page pb-4">
    <!-- Post title as a heading with a link to the full page -->
    <h3 class="h4"><a href="<?php echo $post_link; ?>"><?php echo $post_title; ?></a></h3>
    
    <!-- Row for thumbnail/icon and excerpt content -->
    <div class="row">
        <!-- Column for thumbnail or default icon (2/12 width) -->
        <div class="col-2">
        <?php if ( $post_thumb ) {
            // If thumbnail exists, output it with the post title as alt text
            echo '<img src="'.$post_thumb.'" alt="'. $post_title .'"/>';
        } else { ?>
            <!-- Fallback: Page icon as a base64-encoded PNG image -->
            <img src="data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMTAwIiBoZWlnaHQ9IjEwMCIgdmlld0JveD0iMCAwIDI0IDI0IiBmaWxsPSJub25lIiB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciPgo8cGF0aCBkPSJNMTkuNSAxNC4yNVYxMS42MjVDMTkuNSA5Ljc2MTA0IDE3Ljk4OSA4LjI1IDE2LjEyNSA4LjI1SDE0LjYyNUMxNC4wMDM3IDguMjUgMTMuNSA3Ljc0NjMyIDEzLjUgNy4xMjVWNS42MjVDMTMuNSAzLjc2MTA0IDExLjk4OSAyLjI1IDEwLjEyNSAyLjI1SDguMjVNOC4yNSAxNUgxNS43NU04LjI1IDE4SDEyTTEwLjUgMi4yNUg1LjYyNUM1LjAwMzY4IDIuMjUgNC41IDIuNzUzNjggNC41IDMuMzc1VjIwLjYyNUM0LjUgMjEuMjQ2MyA1LjAwMzY4IDIxLjc1IDUuNjI1IDIxLjc1SDE4LjM3NUMxOC45OTYzIDIxLjc1IDE5LjUgMjEuMjQ2MyAxOS41IDIwLjYyNVYxMS4yNUMxOS41IDYuMjc5NDQgMTUuNDcwNiAyLjI1IDEwLjUgMi4yNVoiIHN0cm9rZT0iI0E5QTdBOCIgc3Ryb2tlLXdpZHRoPSIxLjUiIHN0cm9rZS1saW5lY2FwPSJyb3VuZCIgc3Ryb2tlLWxpbmVqb2luPSJyb3VuZCIvPgo8L3N2Zz4K" alt="Page Icon - No image available"/>
        <?php } ?>
        <!-- Label indicating the post type -->
        <div class="search-type">Page</div>
        </div>
        <!-- Main content column (10/12 width) -->
        <div class="col-10">
            <!-- Display the excerpt -->
            <div class="search-excerpt"><?php echo $excerpt; ?></div>
        </div>
    </div>
</article>
