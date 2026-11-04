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
 * Get the post title
 */
$post_title = get_the_title( $post_id );

/**
 * Override the post title with Provider full name, including degree
 */
$degrees = get_field( 'physician_degree', $post_id );
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
$full_name = get_field( 'physician_first_name', $post_id ) .' ' .(get_field( 'physician_middle_name', $post_id ) ? get_field( 'physician_middle_name', $post_id ) . ' ' : '') . get_field( 'physician_last_name', $post_id ) . (get_field( 'physician_pedigree', $post_id ) ? '&nbsp;' . get_field( 'physician_pedigree', $post_id ) : '') . ( $degree_list ? ', ' . $degree_list : '' );
$post_title = $full_name;

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
 * Generate excerpt specifically for location custom post type using ACF fields
 */
if ( empty($excerpt) ) {
    /* Make a custom excerpt */
    // First, try to get the 'physician_short_clinical_bio' field and trim if available
    $excerpt = get_field( 'physician_short_clinical_bio', $post_id );
    if ($excerpt) {
        $excerpt = wp_trim_words( $excerpt, 20, '... ' );
    }
    
    // If still empty, get the 'physician_clinical_bio' field, strip tags, and trim
    if (empty($excerpt)){
        $about_doc = get_field( 'physician_clinical_bio', $post_id );
        if ($about_doc){
            $excerpt = wp_trim_words(wp_strip_all_tags($about_doc), 20, '...');
        }
    }
    // If still empty, get the 'location_about' field, strip tags, and trim
    if (empty($excerpt)) {
        $physician_resident = get_field( 'physician_resident', $post_id );
        $provider_specialty = '';
		$provider_specialty_term = '';
		$provider_specialty_name = '';
		$provider_occupation_title = '';

		if ( $physician_resident ) {
            // Clinical Occupation Title
            $provider_occupation_title = 'Resident Physician';

        } else {
            // Clinical Specialty
            $provider_specialty = get_field( 'physician_title', $post_id );

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
