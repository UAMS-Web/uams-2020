<?php

/**
 * Author: Sridhar Katakam
 * Link: https://sridharkatakam.com/
 */

remove_action( 'genesis_loop', 'genesis_do_loop' );
add_action( 'genesis_loop', 'uamswp_do_search_loop' );

remove_action( 'genesis_after_header', 'page_options', 5 );

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
    $s = isset( $_GET["s"] ) ? esc_html($_GET["s"]) : "";

    // store the post type from the URL string.
    $post_type = isset( $_GET["type"] ) ? esc_html($_GET["type"]) : "";

    if ( $post_type ) {
        // $post_type = $_GET['post_type'];
        if ('provider' == $post_type) {

            $paged = get_query_var( 'paged' ) ? get_query_var( 'paged' ) : 1;

            // accepts any wp_query args.
            $args = (array(
                's' => $s,
                'post_type' => $post_type,
                'posts_per_page' => 6,
                'paged' => $paged,
            ));

            uamswp_custom_loop_base($args);

            echo '<div class="uams-module bg-auto">';
            echo '<div class="container-fluid">';
            echo '<div class="search-content row">';
            echo '<div class="col-12">';
            echo '<h2 class="module-title">Providers</h2>';

            if ( have_posts() ) :

                echo '<div class="card-list-container"><div class="card-list card-list-doctors facetwp-template">';

            while ( have_posts() ) : the_post();

            $id =get_the_ID();
            include( WP_PLUGIN_DIR . '/UAMSWP-Find-a-Doc/templates/loops/physician-card.php' );

            endwhile;
            echo '</div></div>';
            genesis_posts_nav();

            else :
                echo '<div class="module-body text-center"><p>Sorry, no content matched your criteria.</p></div>';
            endif;


            echo '</div>'; // .col-12
            echo '</div>'; // .search-content
            echo '</div>'; // .container-fluid
            echo '</div>'; // .uams-module

        } elseif('location' == $post_type) {

            $paged = get_query_var( 'paged' ) ? get_query_var( 'paged' ) : 1;

            // accepts any wp_query args.
            $args = (array(
                's' => $s,
                'post_type' => $post_type,
                'posts_per_page' => 10,
                'paged' => $paged,
            ));

            uamswp_custom_loop_base($args);

            echo '<div class="uams-module bg-auto">';
            echo '<div class="container-fluid">';
            echo '<div class="search-content row">';
            echo '<div class="col-12">';
            echo '<h2 class="module-title">Locations</h2>';

            if ( have_posts() ) :

                echo '<div class="card-list-container location-card-list-container"><div class="card-list facetwp-template">';

            while ( have_posts() ) : the_post();

            $id =get_the_ID();
            include( WP_PLUGIN_DIR . '/UAMSWP-Find-a-Doc/templates/loops/location-card.php' );

            endwhile;
            echo '</div></div>';
            // More results link.
            genesis_posts_nav();
            else :
                echo '<div class="module-body text-center"><p>Sorry, no content matched your criteria.</p></div>';
            endif;

            echo '</div>'; // .col-12
            echo '</div>'; // .search-content
            echo '</div>'; // .container-fluid
            echo '</div>'; // .uams-module

        } elseif('expertise' == $post_type) {

            $paged = get_query_var( 'paged' ) ? get_query_var( 'paged' ) : 1;

            // accepts any wp_query args.
            $args = (array(
                's' => $s,
                'post_type' => $post_type,
                'posts_per_page' => 10,
                'paged' => $paged,
            ));

            uamswp_custom_loop_base($args);

            echo '<div class="uams-module bg-auto">';
            echo '<div class="container-fluid">';
            echo '<div class="search-content row">';
            echo '<div class="col-12">';
            echo '<h2 class="module-title">Services</h2>';

            if ( have_posts() ) :

                echo '<div class="card-list-container"><div class="card-list card-list-services facetwp-template">';

            while ( have_posts() ) : the_post();

            $id =get_the_ID();
            include( WP_PLUGIN_DIR . '/UAMSWP-Find-a-Doc/templates/loops/service-card.php' );

            endwhile;
            echo '</div></div>';
            genesis_posts_nav();

            else :
                echo '<div class="module-body text-center"><p>Sorry, no content matched your criteria.</p></div>';
            endif;


            echo '</div>'; // .col-12
            echo '</div>'; // .search-content
            echo '</div>'; // .container-fluid
            echo '</div>'; // .uams-module

        } elseif('condition' == $post_type) {

            $paged = get_query_var( 'paged' ) ? get_query_var( 'paged' ) : 1;
            $taxonomy = 'condition';

            // accepts any wp_query args.
            $args = (array(
                's' => $s,
                'post_type' => $post_type,
                'posts_per_page' => 10,
                'paged' => $paged,
            ));

            uamswp_custom_loop_base($args);

            echo '<div class="uams-module bg-auto">';
            echo '<div class="container-fluid">';
            echo '<div class="search-content row">';
            echo '<div class="col-12">';
            echo '<h2 class="module-title">Conditions</h2>';
            echo '<div class="module-body">';

            if ( have_posts() ) :



            while ( have_posts() ) : the_post();

            	$post_title = get_the_title();
                $post_link = get_the_permalink();
                $tax = get_term_by("name", $post_title, $taxonomy);
                $post_id = $tax->term_id;
                $title = '<h2 class="entry-title" itemprop="headline"><a href="' . $post_link . '">' . $post_title . '</a></h2>';
                $content = get_field($taxonomy.'_content', $taxonomy.'_'.$post_id);

                echo '<article class="'. $taxonomy .'-'. $post_id .' entry">';
                echo '<header class="entry-header">';
                echo $title;
                echo '</header>';
                echo '<div class="entry-content clearfix">';
                // echo $taxonomy.'_'.$post_id;
                echo $content ? '<p>'. wp_trim_words($content, 30) .'... <a class="more-link" href="' . $post_link . '">Continue Reading</a><p>' : '';
                echo '</div>';
                echo '</article>';

            endwhile;

            genesis_posts_nav();

            else :
                echo '<div class="module-body text-center"><p>Sorry, no content matched your criteria.</p></div>';
            endif;

			echo '</div>'; // .module-body
            echo '</div>'; // .col-12
            echo '</div>'; // .search-content
            echo '</div>'; // .container-fluid
            echo '</div>'; // .uams-module

        } elseif('treatment' == $post_type) {

            $paged = get_query_var( 'paged' ) ? get_query_var( 'paged' ) : 1;
            $taxonomy = 'treatment';

            // accepts any wp_query args.
            $args = (array(
                's' => $s,
                'post_type' => $post_type,
                'posts_per_page' => 10,
                'paged' => $paged,
            ));

            uamswp_custom_loop_base($args);

            echo '<div class="uams-module bg-auto">';
            echo '<div class="container-fluid">';
            echo '<div class="search-content row">';
            echo '<div class="col-12">';
            echo '<h2 class="module-title">Treatments &amp; Procedures</h2>';
            echo '<div class="module-body">';

            if ( have_posts() ) :



            while ( have_posts() ) : the_post();

            	$post_title = get_the_title();
                $post_link = get_the_permalink();
                $tax = get_term_by("name", $post_title, $taxonomy);
                $post_id = $tax->term_id;
                $title = '<h2 class="entry-title" itemprop="headline"><a href="' . $post_link . '">' . $post_title . '</a></h2>';
                $content = get_field($taxonomy.'_content', $taxonomy.'_'.$post_id);

                echo '<article class="'. $taxonomy .'-'. $post_id .' entry">';
                echo '<header class="entry-header">';
                echo $title;
                echo '</header>';
                echo '<div class="entry-content clearfix">';
                // echo $taxonomy.'_'.$post_id;
                echo $content ? '<p>'. wp_trim_words($content, 30) .'... <a class="more-link" href="' . $post_link . '">Continue Reading</a><p>' : '';
                echo '</div>';
                echo '</article>';

            endwhile;

            genesis_posts_nav();

            else :
                echo '<div class="module-body text-center"><p>Sorry, no content matched your criteria.</p></div>';
            endif;

			echo '</div>'; // .module-body
            echo '</div>'; // .col-12
            echo '</div>'; // .search-content
            echo '</div>'; // .container-fluid
            echo '</div>'; // .uams-module

        } else {
            $paged = get_query_var( 'paged' ) ? get_query_var( 'paged' ) : 1;

            // accepts any wp_query args.
            $args = (array(
                's' => $s,
                'post_type' => $post_type,
                'paged' => $paged,
            ));

            $post_type_text = ucfirst($post_type);
            if (substr($post_type_text, -1) !== 's') {
                $post_type_text = $post_type_text . "s";
            }

            echo '<div class="uams-module bg-auto">';
            echo '<div class="container-fluid">';
            echo '<div class="search-content row">';
            echo '<div class="col-12">';
                echo '<h2 class="module-title">' . $post_type_text . '</h2>';
                echo '<div class="module-body">';
                    // Loop actions.
                    uamswp_loop_layout();

                    // custom genesis loop with the above query parameters and hooks.
                    // genesis_custom_loop( $args );
                    uamswp_custom_loop( $args );

                echo '</div>'; // module-body
            echo '</div>'; // col-12
            echo '</div>'; // search-content
            echo '</div>'; // container-fluid
            echo '</div>'; // uams-module
        }
    } else {
        // create an array variable with specific post types in your desired order.
        $post_types = array( 'page', 'post' );

        echo '<div class="uams-module bg-white">';
        echo '<div class="container-fluid">';
        echo '<div class="search-content row">';

            foreach ( $post_types as $post_type ) {
                // get the search term entered by user.
                $s = isset( $_GET["s"] ) ? esc_html($_GET["s"]) : "";

                // accepts any wp_query args.
                $args = (array(
                    's' => $s,
                    'post_type' => $post_type,
                    'posts_per_page' => 5,
                ));

                uamswp_custom_loop_base($args);

                $post_type_text = ucfirst($post_type);
                if (substr($post_type_text, -1) !== 's') {
                    $post_type_text = $post_type_text . "s";
                }

                echo '<div class="col-12 col-md-6 post-type ' . $post_type . '"><div class="inner-container content-width"><h2 class="post-type-heading">' . $post_type_text . '</h2>';
                if ( have_posts() ) {

                    while ( have_posts() ) : the_post();
                        global $wp_query;

                        switch_to_blog($wp_query->post->blog_id);

                        $post_count = $wp_query->found_posts;
                        $post_title = get_the_title( $wp_query->post->ID );
                        $post_link = get_the_permalink( $wp_query->post->ID );
                        $title = '<h3 class="h4" data-id="'. $wp_query->post->ID .'"><a href="' . $post_link . '">' . $post_title . '</a></h3>';
                        if ( has_excerpt( $wp_query->post->blog_id ) ) {
                        	$content = get_the_excerpt( $wp_query->post->ID );
                        } else {
	                        $content = wp_trim_excerpt( "", $wp_query->post->ID );
                        }
                        echo $title ? $title : '';
                        // echo $taxonomy.'_'.$post_id;
                        echo $content ? '<p>'. $content . '</p>' : '';

                        restore_current_blog();

                    endwhile;
                    if ($post_count > 5) {
                        // More results link.
                        printf( '<div class="more"><a href="%s" class="btn btn-outline-primary">More results</a></div>', trailingslashit( home_url() ) . '?s=' . $s . '&type=' . $post_type );
                    }
                } else {
                    echo "<p>Sorry, no content matched your criteria.</p>";
                }
                echo '</div></div>';
                wp_reset_query();
            }


        echo '</div>'; // .search-content
        echo '</div>'; // .container-fluid
        echo '</div>'; // .uams-module

        if (class_exists('UAMSPhysicians')) { // Add doctors, location, and services

        // Begin Providers

            $s = isset( $_GET["s"] ) ? esc_html($_GET["s"]) : "";


            /* Providers */
            $post_type = array('provider');

            // accepts any wp_query args.
            $args = (array(
                's' => $s,
                'post_type' => $post_type,
                'posts_per_page' => 6,
            ));

            uamswp_custom_loop_base($args);



            if ( have_posts() ) {

	             echo '<div class="uams-module bg-auto">';
            echo '<div class="container-fluid">';
            echo '<div class="search-content row">';
            echo '<div class="col-12">';

            echo '<h2 class="module-title">Providers</h2>';

                echo '<div class="card-list-container"><div class="card-list card-list-doctors card-list-doctors-count-6 facetwp-template">';

                while ( have_posts() ) : the_post();

                    global $wp_query;

                    $id =get_the_ID();
                    include( WP_PLUGIN_DIR . '/UAMSWP-Find-a-Doc/templates/loops/physician-card.php' );

                endwhile;
                echo '</div></div>';
                // More results link.
                if ($wp_query->found_posts > 6) {
                    printf( '<div class="more"><a href="%s" class="btn btn-outline-primary">More results</a></div>', trailingslashit( home_url() ) . '?s=' . $s . '&type=' . $post_type[0] );
                }

                 echo '</div>'; // .col-12
            echo '</div>'; // .search-content
            echo '</div>'; // .container-fluid
            echo '</div>'; // .uams-module

/*
            } else {
                echo '<div class="module-body text-center"><p>Sorry, no content matched your criteria.</p></div>';
*/
            }



            wp_reset_query();
        // End Providers

        // Begin Conditions & Treatments
        $conditions_treatments = '';
        $show_conditions_treatments = false;
        // If possible, nest within a condition so that it only displays if there are taxonomy results.
        $conditions_treatments .= '<div class="uams-module bg-auto">';
        $conditions_treatments .= '<div class="container-fluid">';
        $conditions_treatments .= '<div class="search-content row">';

            $taxonomies = array();

                array_push($taxonomies, 'condition', 'treatment' );

                foreach ( $taxonomies as $taxonomy ) {
                    // get the search term entered by user.
                    $s = isset( $_GET["s"] ) ? esc_html($_GET["s"]) : "";

                    // accepts any wp_query args.
                    $args = (array(
                        's' => $s,
                        'post_type' => $taxonomy,
                        'posts_per_page' => 5,
                    ));

                    // $term_query = new WP_Term_Query( $args );
                    $taxonomy_text = get_taxonomy($taxonomy)->labels->name;
                    // if (substr($taxonomy_text, -1) !== 's') {
                    //     $taxonomy_text = $taxonomy_text . "s";
                    // }

                    // echo '<div class="col-12 col-md-6 post-type ' . $taxonomy . '"><div class="inner-container content-width"><h2 class="post-type-heading">' . $taxonomy_text . '</h2>';
                    //     // Loop actions.
                    //     uamswp_loop_layout();

                    //     // remove archive pagination.
                    //     remove_action( 'genesis_after_endwhile', 'genesis_posts_nav' );

                    //     // custom genesis loop with the above query parameters and hooks.
                    //     //genesis_custom_loop( $args );
                    //     uamswp_custom_loop( $args );

                    //     // More results link.
                    //     printf( '<a href="%s" class="btn btn-outline-primary">More results</a>', trailingslashit( home_url() ) . '?s=' . $s . '&type=' . $taxonomy );
                    // echo '</div></div>';

                    uamswp_custom_loop_base($args);

                    if ( have_posts() ) :
						$show_conditions_treatments = true;
                        $conditions_treatments .= '<div class="col-12 col-md-6 post-type ' . $taxonomy . '"><div class="inner-container content-width"><h2 class="post-type-heading">' . $taxonomy_text . '</h2>';

                        while ( have_posts() ) : the_post();

                            $post_count = $wp_query->found_posts;
                            $post_title = get_the_title();
                            $post_link = get_the_permalink();
                            $tax = get_term_by("name", $post_title, $taxonomy);
                            $post_id = $tax->term_id;
                            $title = '<h3 class="h4" itemprop="headline"><a href="' . $post_link . '">' . $post_title . '</a></h3>';
                            $content = get_field($taxonomy.'_content', $taxonomy.'_'.$post_id);
                            if ('treatment' == $taxonomy) {
	                            $content = get_field('treatment_procedure_content', $taxonomy.'_'.$post_id); // Fix for naming convention
                            }

                            $conditions_treatments .= $title;
                            // echo $taxonomy.'_'.$post_id;
                            $conditions_treatments .= $content ? '<p>'. wp_trim_words($content, 30) .'<p>' : '';

                        endwhile;
                        if ($post_count > 5) {
                            // More results link.
                            $conditions_treatments .= sprintf( '<div class="more"><a href="%s" class="btn btn-outline-primary">More results</a></div></div>', trailingslashit( home_url() ) . '?s=' . $s . '&type=' . $taxonomy );
                        } else {
                            $conditions_treatments .= '</div>';
                        }
                        $conditions_treatments .= '</div>';
                    else :
                        $conditions_treatments .=' <div class="col-12 col-md-6 post-type ' . $taxonomy . '"><div class="inner-container content-width"><h2 class="post-type-heading">' . $taxonomy_text . '</h2>';
                        $conditions_treatments .= '<p>Sorry, no content matched your criteria.</p>';
                        $conditions_treatments .= '</div></div>';
                    endif;
                    // foreach ( $term_query->terms as $term ) {
                    //     echo '<article class="'. $taxonomy .'-'. $term->slug .' entry">';
                    //     echo '    <header class="entry-header"><h2 class="entry-title" itemprop="headline"><a class="entry-title-link" rel="bookmark" href="'. get_term_link( $term->term_id , $taxonomy ) .'">'. $term->name .'</a></h2></header>';
                    //     echo '   <div class="entry-content clearfix"><p>'. wp_trim_words( $term->description , 20, '... <a class="more-link" href="' . get_term_link( $term->term_id , $taxonomy ) . '">Continue Reading</a>' ) .'</p></div>';
                    //     echo '</article>';
                    // }
                    wp_reset_query();
                }

        $conditions_treatments .= '</div>'; // .search-content
        $conditions_treatments .= '</div>'; // .container-fluid
        $conditions_treatments .= '</div>'; // .uams-module

        if ($show_conditions_treatments) {
	        echo $conditions_treatments;
        }

        // End Conditions and Treatments

        // Begin Locations
            $post_type = array('location');



            // accepts any wp_query args.
            $args = (array(
                's' => $s,
                'post_type' => $post_type,
                'posts_per_page' => 4,
            ));

            uamswp_custom_loop_base($args);


            if ( have_posts() ) {

	            echo '<div class="uams-module bg-auto">';
            echo '<div class="container-fluid">';
            echo '<div class="search-content row">';
            echo '<div class="col-12">';

            echo '<h2 class="module-title">Locations</h2>';

                echo '<div class="card-list-container location-card-list-container"><div class="card-list facetwp-template">';

                while ( have_posts() ) : the_post();

                    global $wp_query;

                    $id =get_the_ID();
                    include( WP_PLUGIN_DIR . '/UAMSWP-Find-a-Doc/templates/loops/location-card.php' );

                endwhile;

                echo '</div></div>';
                // More results link.
                if ($wp_query->found_posts > 4) {
                    printf( '<div class="more"><a href="%s" class="btn btn-outline-primary">More results</a></div>', trailingslashit( home_url() ) . '?s=' . $s . '&type=' . $post_type[0] );
                }

/*
            } else {
                echo '<div class="module-body text-center"><p>Sorry, no content matched your criteria.</p></div>';
*/

			echo '</div>'; // .col-12
            echo '</div>'; // .search-content
            echo '</div>'; // .container-fluid
            echo '</div>'; // .uams-module
            }


            wp_reset_query();
        // End Locations

        // Begin Areas of Expertise
            $post_type = array('expertise');



            // accepts any wp_query args.
            $args = (array(
                's' => $s,
                'post_type' => $post_type,
                'posts_per_page' => 4,
            ));

            uamswp_custom_loop_base($args);



            if ( have_posts() ) {

	            echo '<div class="uams-module bg-auto">';
	            echo '<div class="container-fluid">';
	            echo '<div class="search-content row">';
	            echo '<div class="col-12">';

            	echo '<h2 class="module-title">Areas of Expertise</h2>';
                echo '<div class="card-list-container"><div class="card-list card-list-expertise facetwp-template">';

                while ( have_posts() ) : the_post();

                    global $wp_query;

                    $id =get_the_ID();
                    include( WP_PLUGIN_DIR . '/UAMSWP-Find-a-Doc/templates/loops/expertise-card.php' );

                endwhile;

                echo '</div></div>';
                // More results link.
                if ($wp_query->found_posts > 4) {
                    printf( '<div class="more"><a href="%s" class="btn btn-outline-primary">More results</a></div>', trailingslashit( home_url() ) . '?s=' . $s . '&type=' . $post_type[0] );
                }

/*
            } else {
                echo '<div class="module-body text-center"><p>Sorry, no content matched your criteria.</p></div>';
*/

				echo '</div>'; // .col-12
	            echo '</div>'; // .search-content
	            echo '</div>'; // .container-fluid
	            echo '</div>'; // .uams-module
            }


            wp_reset_query();
        // End Areas of Expertise
        }
        ?>
        <div class="uams-module bg-auto">
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

    add_filter( 'genesis_post_title_output', 'uamswp_search_title' );

    // force excerpts.
    // add_filter( 'genesis_pre_get_option_content_archive', 'uamswp_show_excerpts' );

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
    $s = isset( $_GET["s"] ) ? esc_html($_GET["s"]) : "";
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

function uamswp_search_title( $title ) {
    global $post;
    switch_to_blog($post->blog_id);
    $post_title = get_the_title( $post->ID );
    $post_link = get_the_permalink( $post->ID );
    $title = '<h2 class="entry-title" itemprop="headline"><a href="' . $post_link . '">' . $post_title . '</a></h2>';
    restore_current_blog();

    return $title;
}

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

function uamswp_custom_loop_base( $args = array() ) {

	global $wp_query, $more;

	$defaults = []; // For forward compatibility.
	$args     = apply_filters( 'genesis_custom_loop_args', wp_parse_args( $args, $defaults ), $args, $defaults );

    $wp_query = new WP_Query( $args ); // phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited -- Reset later.

    // added this based on http://www.relevanssi.com/knowledge-base/relevanssi_do_query/
	relevanssi_do_query( $wp_query );

	// Only set $more to 0 if we're on an archive.
	$more = is_singular() ? $more : 0; // phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited -- Handle archives.

}

add_filter( 'get_the_excerpt', 'strip_shortcodes', 20 );

// modify the Excerpt read more link.
add_filter( 'excerpt_more', 'new_excerpt_more' );

// modify the length of post excerpts.
add_filter( 'excerpt_length', 'uamswp_excerpt_length' );

genesis();
