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
 * Override the post title with Provider full name, including degree
 */
$degrees = get_field('physician_degree');
$degree_list = '';
$i = 1;
if ($degrees){
    foreach( $degrees as $degree ):
        $degree_name = get_term( $degree, 'degree');
        $degree_list .= $degree_name->name;
        if( count($degrees) > $i ) {
            $degree_list .= ", ";
        }
        $i++;
    endforeach;
}
$full_name = get_field('physician_first_name') .' ' .(get_field('physician_middle_name') ? get_field('physician_middle_name') . ' ' : '') . get_field('physician_last_name') . (get_field('physician_pedigree') ? '&nbsp;' . get_field('physician_pedigree') : '') . ( $degree_list ? ', ' . $degree_list : '' );
$post_title = $full_name;

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
    // First, try to get the 'physician_short_clinical_bio' field and trim if available
    $excerpt = get_field('physician_short_clinical_bio');
    if ($excerpt) {
        $excerpt = wp_trim_words( $excerpt, 20, '... ' );
    }
    
    // If still empty, get the 'physician_clinical_bio' field, strip tags, and trim
    if (empty($excerpt)){
        $about_doc = get_field('physician_clinical_bio');
        if ($about_doc){
            $excerpt = wp_trim_words(wp_strip_all_tags($about_doc), 20, '...');
        }
    }
    // If still empty, get the 'location_about' field, strip tags, and trim
    if (empty($excerpt)) {
        $physician_resident = get_field('physician_resident');
        $provider_specialty = '';
		$provider_specialty_term = '';
		$provider_specialty_name = '';
		$provider_occupation_title = '';

		if ( $physician_resident ) {
            // Clinical Occupation Title
            $provider_occupation_title = $physician_resident_title_name;

        } else {
            // Clinical Specialty
            $provider_specialty = get_field('physician_title');

            // Clinical Occupation Title
                if ( $provider_specialty ) {
                    $provider_specialty_term = get_term($provider_specialty, 'clinical_title');
                    if ( is_object($provider_specialty_term) ) {
                        // Get term name
                            $provider_specialty_name = $provider_specialty_term->name;
                        // Get occupational title field from term
                            $provider_occupation_title = get_field('clinical_specialization_title', $provider_specialty_term) ?? null;
                        // Set occupational title from term name as a fallback
                            if ( !$provider_occupation_title ) {
                                $provider_occupation_title = $provider_specialty_name;
                            }
                    }

                }

        }
        if ( $provider_occupation_title && !empty($provider_occupation_title) ) {
            $excerpt = $provider_occupation_title . '<br/><br/>';
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
                <!-- Fallback: Doctor icon as a base64-encoded PNG image (note: may be a generic icon for locations) -->
                <img src="data:image/svg+xml;base64,PD94bWwgdmVyc2lvbj0iMS4wIiBlbmNvZGluZz0iVVRGLTgiPz48c3ZnIGlkPSJhIiB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHZpZXdCb3g9IjAgMCAxMDAgMTAwIj48cGF0aCBkPSJNNjIuMjIsNDcuOTFoLTI0LjcyYy03LjM2LDAtMTMuMzYsNS45OS0xMy4zNiwxMy4zNnYzMS4wMWMwLC41NS40NSwxLDEsMWg0OS40NGMuNTUsMCwxLS40NSwxLTF2LTMxLjAxYzAtNy4zNy01Ljk5LTEzLjM2LTEzLjM2LTEzLjM2Wk0zNy41LDcyLjA2YzEuODEsMCwzLjI3LDEuNDcsMy4yNywzLjI3cy0xLjQ3LDMuMjctMy4yNywzLjI3LTMuMjctMS40Ny0zLjI3LTMuMjcsMS40Ny0zLjI3LDMuMjctMy4yN1pNNzMuNTgsOTEuMjhIMjYuMTN2LTMwLjAxYzAtNS45Myw0LjU3LTEwLjgxLDEwLjM3LTExLjMxdjIwLjIxYy0yLjQzLjQ3LTQuMjcsMi42LTQuMjcsNS4xNywwLDIuOSwyLjM2LDUuMjcsNS4yNyw1LjI3czUuMjctMi4zNiw1LjI3LTUuMjdjMC0yLjU2LTEuODQtNC43LTQuMjctNS4xN3YtMjAuMjZoMjIuNzN2MjAuMjNjLTMuNTUuNDctNi4zLDMuNTEtNi4zLDcuMTh2Ni4yNWMwLC41NS40NSwxLDEsMXMxLS40NSwxLTF2LTYuMjVjMC0yLjksMi4zNi01LjI2LDUuMjYtNS4yNnM1LjI2LDIuMzYsNS4yNiw1LjI2djYuMjVjMCwuNTUuNDUsMSwxLDFzMS0uNDUsMS0xdi02LjI1YzAtMy42NC0yLjctNi42Ni02LjIxLTcuMTZ2LTIwLjE5YzUuOC41MSwxMC4zNyw1LjM4LDEwLjM3LDExLjMxdjMwLjAxaDBaIiBmaWxsPSIjYTlhN2E4IiBzdHJva2U9IiNhOWE3YTgiIHN0cm9rZS1taXRlcmxpbWl0PSIxMCIgc3Ryb2tlLXdpZHRoPSIzIi8+PHBhdGggZD0iTTQ5Ljg2LDQzLjVjMTAuMjIsMCwxOC41My04LjMxLDE4LjUzLTE4LjUzUzYwLjA4LDYuNDQsNDkuODYsNi40NHMtMTguNTMsOC4zMS0xOC41MywxOC41Myw4LjMxLDE4LjUzLDE4LjUzLDE4LjUzWk00OS44Niw4LjQzYzkuMTIsMCwxNi41NCw3LjQyLDE2LjU0LDE2LjU0cy03LjQyLDE2LjU0LTE2LjU0LDE2LjU0LTE2LjU0LTcuNDItMTYuNTQtMTYuNTQsNy40Mi0xNi41NCwxNi41NC0xNi41NFoiIGZpbGw9IiNhOWE3YTgiIHN0cm9rZT0iI2E5YTdhOCIgc3Ryb2tlLW1pdGVybGltaXQ9IjEwIiBzdHJva2Utd2lkdGg9IjMiLz48L3N2Zz4=" alt="Provider Icon - No photo available"/>
        <?php } ?>
        <!-- Label indicating the post type -->
        <div class="search-type"><?php echo $post_type_name; ?></div>
        </div>
        <!-- Main content column (10/12 width) -->
        <div class="col-10">
            <!-- Display the excerpt with a "Continue Reading" link appended -->
            <div class="search-excerpt"><?php echo $excerpt . (str_word_count($excerpt) >= 20 ? ' <a href="'.$post_link.'">View Profile</a>' : ''); ?></div>
        </div>
    </div>
</article>
