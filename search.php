<?php

/**
 * Author: Sridhar Katakam
 * Link: https://sridharkatakam.com/
 */

remove_action( 'genesis_loop', 'genesis_do_loop' );
add_action( 'genesis_loop', 'uamswp_do_search_loop' );

add_action( 'genesis_before_loop', 'uamswp_search_page_before_entry', 5 );
add_action( 'genesis_before_loop', 'uamswp_search_page_heading', 8 );
add_action( 'genesis_after_loop', 'uamswp_search_page_after_entry', 10 );
/**
 * Outputs a custom loop
 *
 * @global mixed $paged current page number if paginated
 * @return void
 */
function uamswp_do_search_loop() {
    // get the search term entered by user.
    $s = isset( $_GET["s"] ) ? $_GET["s"] : "";

    // store the post type from the URL string.
    $post_type = isset( $_GET["post_type"] ) ? $_GET["post_type"] : "";

    if ( $post_type ) {
        // $post_type = $_GET['post_type'];
        $paged = get_query_var( 'paged' ) ? get_query_var( 'paged' ) : 1;

        // accepts any wp_query args.
        $args = (array(
            's' => $s,
            'post_type' => $post_type,
            'order' => 'ASC',
            'orderby' => 'title',
            'paged' => $paged,
        ));

        $post_type_text = ucfirst($post_type); 
        if (substr($post_type_text, -1) !== 's') {
            $post_type_text = $post_type_text . "s";
        }

        echo '<div class="search-content">';
            echo '<div class="post-type ' . $post_type . '"><h1 class="post-type-heading">' . $post_type_text . '</h1>';
                // Loop actions.
                uamswp_loop_layout();

                // custom genesis loop with the above query parameters and hooks.
                // genesis_custom_loop( $args );
                uamswp_custom_loop( $args );

            echo '</div>';
        echo '</div>';
    } else {
        // create an array variable with specific post types in your desired order.
        $post_types = array( 'page', 'post' );

        if (class_exists('UAMS\Find_a_Doc')) { // Add doctors, locations, and services
            array_push($post_types, 'physicians', 'locations', 'services' );
        }
            
        echo '<div class="uams-module bg-white">';
        echo '<div class="container-fluid">';
        echo '<div class="search-content row">';

            foreach ( $post_types as $post_type ) {
                // get the search term entered by user.
                $s = isset( $_GET["s"] ) ? $_GET["s"] : "";

                // accepts any wp_query args.
                $args = (array(
                    's' => $s,
                    'post_type' => $post_type,
                    'posts_per_page' => 5,
                    'order' => 'ASC',
                    'orderby' => 'title',
                ));

                $post_type_text = ucfirst($post_type); 
                if (substr($post_type_text, -1) !== 's') {
                    $post_type_text = $post_type_text . "s";
                }

                echo '<div class="col-12 col-md-6 post-type ' . $post_type . '"><div class="inner-container content-width"><h2 class="post-type-heading">' . $post_type_text . '</h2>';
                    // Loop actions.
                    uamswp_loop_layout();

                    // remove archive pagination.
                    remove_action( 'genesis_after_endwhile', 'genesis_posts_nav' );

                    // custom genesis loop with the above query parameters and hooks.
                    //genesis_custom_loop( $args );
                    uamswp_custom_loop( $args );

                    // More results link.
                    printf( '<a href="%s" class="btn btn-outline-primary">More results</a>', trailingslashit( home_url() ) . '?s=' . $s . '&post_type=' . $post_type );
                echo '</div></div>';
            }

            $taxonomies = array( 'category' );

            foreach ( $taxonomies as $taxonomy ) {
                // get the search term entered by user.
                $s = isset( $_GET["s"] ) ? $_GET["s"] : "";

                // accepts any wp_query args.
                $args = (array(
                    'taxonomy' => $taxonomy,
                    'search' => $s,
                    'number' => 5,
                    'order' => 'ASC',
                    'orderby' => 'name',
                ));

                $term_query = new WP_Term_Query( $args );

                $taxonomy_text = ucfirst($taxonomy); 
                // if (substr($taxonomy_text, -1) !== 's') {
                //     $taxonomy_text = $taxonomy_text . "s";
                // }

                if( ! empty( $term_query->terms ) ) {

                    echo '<div class="taxonomy ' . $taxonomy . '"><h1 class="post-type-heading">' . $taxonomy_text . '</h1>';
                        // Loop actions.
                        uamswp_loop_layout();

                        // remove archive pagination.
                        remove_action( 'genesis_after_endwhile', 'genesis_posts_nav' );

                        // custom genesis loop with the above query parameters and hooks.
                        //genesis_custom_loop( $args );
                        // uamswp_custom_loop( $args );
                        // $terms = get_terms( $args );
                        // print_r($term_query->terms);
                        // print_r( $args );
                        foreach ( $term_query->terms as $term ) {
                            echo '<article class="'. $taxonomy .'-'. $term->slug .' entry">';
                            echo '    <header class="entry-header"><h2 class="entry-title" itemprop="headline"><a class="entry-title-link" rel="bookmark" href="'. get_term_link( $term->term_id , $taxonomy ) .'">'. $term->name .'</a></h2></header>';
                            echo '   <div class="entry-content clearfix"><p>'. wp_trim_words( $term->description , 20, '... <a class="more-link" href="' . get_term_link( $term->term_id , $taxonomy ) . '">Continue Reading</a>' ) .'</p></div>';
                            echo '</article>';
                        }
                        

                        // More results link.
                        printf( '<a href="%s">More results</a>', trailingslashit( home_url() ) . '?s=' . $s . '&taxonomy=' . $taxonomy );
                    echo '</div>';

                }
            }

        echo '</div>'; // .search-content
        echo '</div>'; // .container-fluid
        echo '</div>'; // .uams-module
        ?>
        <div class="uams-module bg-gray">
            <div class="container-fluid">
                <div class="row">
                    <div class="widget_search col-12">
                            <h2 class="module-title">Search All UAMS Sites</h2>
                            <p class="content note">Search UAMS websites on the UAMS.edu and UAMShealth.com&nbsp;domains:</p>
                        <div class="inner-container content-width">
                            <script async src="https://cse.google.com/cse.js?cx=014806496997774146681:pyl1jhwi-bo"></script>
                            <div class="gcse-searchresults-only" data-queryParameterName="s"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <?php
    }

}

/**
 * Arrange elements in the loop.
 */
function uamswp_loop_layout() {
    // remove post info.
    remove_action( 'genesis_entry_header', 'genesis_post_info', 12 );

    // remove post image (from theme settings).
    remove_action( 'genesis_entry_content', 'genesis_do_post_image', 8 );

    // remove entry content.
    // remove_action( 'genesis_entry_content', 'genesis_do_post_content' );

    // remove post content nav.
    remove_action( 'genesis_entry_content', 'genesis_do_post_content_nav', 12 );
    remove_action( 'genesis_entry_content', 'genesis_do_post_permalink', 14 );

    // force content limit.
    add_filter( 'genesis_pre_get_option_content_archive_limit', 'uamswp_content_limit' );

    // modify the Content Limit read more link.
    add_filter( 'get_the_content_more_link', 'uamswp_read_more_link' );

	// remove h1 modifications
    remove_filter( 'genesis_post_title_output', 'uamswp_entry_title_h1' );

    // force excerpts.
    // add_filter( 'genesis_pre_get_option_content_archive', 'uamswp_show_excerpts' );

    // modify the Excerpt read more link.
    add_filter( 'excerpt_more', 'new_excerpt_more' );

    // modify the length of post excerpts.
    add_filter( 'excerpt_length', 'uamswp_excerpt_length' );

    // remove entry footer.
    remove_action( 'genesis_entry_footer', 'genesis_entry_footer_markup_open', 5 );
    remove_action( 'genesis_entry_footer', 'genesis_entry_footer_markup_close', 15 );
    remove_action( 'genesis_entry_footer', 'genesis_post_meta' );
}

function uamswp_content_limit() {
    return '150'; // number of characters
}

function uamswp_read_more_link() {
    return '... <a class="more-link" href="' . get_permalink() . '">Continue Reading</a>';
}

function uamswp_show_excerpts() {
    return 'excerpts';
}

function new_excerpt_more( $more ) {
    return '... <a class="more-link" href="' . get_permalink() . '">Continue Reading</a>';
}

function uamswp_excerpt_length( $length ) {
    return 20; // pull first 20 words
}

function uamswp_search_page_before_entry () {
    echo '<article class="entry" itemtype="https://schema.org/CreativeWork">';
}

function uamswp_search_page_heading () {
    $s = isset( $_GET["s"] ) ? $_GET["s"] : "";
    echo '<header class="entry-header"><h1 class="entry-title" itemprop="headline">Search results for: '. $s .'</h1></header>';
}

function uamswp_search_page_after_entry () {
    echo '</article>';
}

//* Prefix search breadcrumb trail with the text 'Search results for'
function uamswp_prefix_search_breadcrumb( $args ) {
  
    $args['labels']['search'] = 'Search results for ';
    
    return $args;

}
add_filter( 'genesis_breadcrumb_args', 'uamswp_prefix_search_breadcrumb' );

function uamswp_custom_loop( $args = array() ) {

	global $wp_query, $more;

	$defaults = []; // For forward compatibility.
	$args     = apply_filters( 'genesis_custom_loop_args', wp_parse_args( $args, $defaults ), $args, $defaults );

    $wp_query = new WP_Query( $args ); // phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited -- Reset later.
    
    // added this based on http://www.relevanssi.com/knowledge-base/relevanssi_do_query/
	relevanssi_do_query( $wp_query );

	// Only set $more to 0 if we're on an archive.
	$more = is_singular() ? $more : 0; // phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited -- Handle archives.

	genesis_standard_loop();

	// Restore original query.
	wp_reset_query(); // phpcs:ignore WordPress.WP.DiscouragedFunctions.wp_reset_query_wp_reset_query -- Making sure the query is really reset.

}

genesis();
