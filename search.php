<?php
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


        echo '</div>'; // .search-content
        echo '</div>'; // .container-fluid
        echo '</div>'; // .uams-module
            
        // If possible, nest within a condition so that it only displays if there are taxonomy results.
        echo '<div class="uams-module bg-auto">';
        echo '<div class="container-fluid">';
        echo '<div class="search-content row">';

            $taxonomies = array();

            if (class_exists('UAMSPhysicians')) { // Add doctors, locations, and services
                array_push($taxonomies, 'condition', 'treatment_procedure' );
            }

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

                    echo '<div class="col-12 col-md-6 taxonomy ' . $taxonomy . '"><div class="inner-container content-width"><h2 class="post-type-heading">' . $taxonomy_text . '</h2>';
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
                        printf( '<a href="%s" class="btn btn-outline-primary">More results</a>', trailingslashit( home_url() ) . '?s=' . $s . '&taxonomy=' . $taxonomy );
                    echo '</div></div>';

                }
            }

        echo '</div>'; // .search-content
        echo '</div>'; // .container-fluid
        echo '</div>'; // .uams-module

        if (class_exists('UAMSPhysicians')) { // Add doctors, locations, and services
            
            $s = isset( $_GET["s"] ) ? $_GET["s"] : "";


            /* Physicians */
            $post_type = array('physicians');

            echo '<div class="uams-module bg-auto">';
            echo '<div class="container-fluid">';
            echo '<div class="search-content row">';

            // accepts any wp_query args.
            $args = (array(
                's' => $s,
                'post_type' => $post_type,
                'posts_per_page' => 4,
                'order' => 'ASC',
                'orderby' => 'title',
            ));

            uamswp_custom_loop_base($args);

            if ( have_posts() ) : while ( have_posts() ) : the_post(); 
            echo '<div class="card-list-container"><div class="card-list card-list-doctors facetwp-template"><div class="inner-container content-width"><h2 class="post-type-heading">Doctors</h2>';

            
            $degrees = get_field('physician_degree');
            $degree_list = '';
            $i = 1;
            if ( $degrees ) {
                foreach( $degrees as $degree ):
                    $degree_name = get_term( $degree, 'degree');
                    $degree_list .= $degree_name->name;
                    if( count($degrees) > $i ) {
                        $degree_list .= ", ";
                    }
                    $i++;
                endforeach; 
            } 
            ?>
            <?php $full_name = get_field('physician_first_name') .' ' .(get_field('physician_middle_name') ? get_field('physician_middle_name') . ' ' : '') . get_field('physician_last_name') .  ( $degree_list ? ', ' . $degree_list : '' ); ?>
            <div class="card">
                <picture>
                    <?php if ( function_exists( 'fly_add_image_size' ) ) { ?>
                    <source srcset="<?php echo image_sizer(get_post_thumbnail_id(), 510, 680, 'center', 'center'); ?>"
                        media="(min-width: 1px) and (-webkit-min-device-pixel-ratio: 2), 
                        (min-width: 1px) and (min-resolution: 192dpi)">
                    <source srcset="<?php echo image_sizer(get_post_thumbnail_id(), 255, 340, 'center', 'center'); ?>"
                        media="(min-width: 1px)">
                    <img src="<?php echo image_sizer(get_post_thumbnail_id(), 255, 340, 'center', 'center'); ?>" class="card-img-top" alt="<?php echo $full_name; ?>" />
                    <?php } else { ?>
                    <?php the_post_thumbnail( 'medium',  array( 'itemprop' => 'image', 'class' => 'card-img-top' ) ); ?>
                    <?php } //endif ?>
                </picture>
                <div class="card-body">
                        <h3 class="card-title">
                            <span class="name"><?php echo $full_name; ?></span>
                            <?php 
                            if(! empty( get_field('physician_clinical_title') ) || ! empty( get_field('physician_department') ) ){
                                echo '<span class="subtitle">';
                                echo (get_field('physician_clinical_title') ? get_field('physician_clinical_title')->name : '');
                                echo ((! empty( get_field('physician_clinical_title') )) && (! empty( get_field('physician_department') ) ) ? ', ' : '' );
                                echo (get_field('physician_department') ? get_field('physician_department')->name : '');
                                echo '</span>';
                            }
                            ?>
                        </h3>
                    <a href="<?php the_permalink(); ?>" class="btn btn-primary stretched-link" aria-label="View profile for <?php echo $full_name; ?>">View Profile</a>
                </div>
            </div>
            <?php 
            // More results link.
            printf( '<a href="%s" class="btn btn-outline-primary">More results</a>', trailingslashit( home_url() ) . '?s=' . $s . '&post_type=' . $post_type );
            echo '</div></div></div>';
            endwhile; 
            else :
                echo "<p>Sorry, no content matched your criteria.</p>";
            endif; 


            echo '</div>'; // .search-content
            echo '</div>'; // .container-fluid
            echo '</div>'; // .uams-module

            /* End Physicians */ 
            wp_reset_query();
            /* Locations */
            $post_type = array('locations');

            echo '<div class="uams-module bg-auto">';
            echo '<div class="container-fluid">';
            echo '<div class="search-content row">';

            // accepts any wp_query args.
            $args = (array(
                's' => $s,
                'post_type' => $post_type,
                'posts_per_page' => 4,
                'order' => 'ASC',
                'orderby' => 'title',
            ));

            uamswp_custom_loop_base($args);

            if ( have_posts() ) :
                echo '<div class="card-list-container"><div class="card-list card-list-locations facetwp-template"><div class="inner-container content-width"><h2 class="post-type-heading">Locations</h2>';    
            while ( have_posts() ) : the_post(); 
            ?>
                <div class="card">
                <a href="<?php echo get_permalink(); ?>" aria-label="Go to location page for <?php the_title(); ?>"><?php if ( has_post_thumbnail() ) { ?>
                <?php the_post_thumbnail('aspect-16-9-small', ['class' => 'card-img-top']); ?>
                <?php } else { ?>
                <img src="/wp-content/plugins/UAMSWP-Find-a-Doc/assets/svg/no-image_16-9.svg" alt="" class="card-image-top" />
                <?php } ?></a>
                <div class="card-body">
                    <h3 class="card-title">
                        <span class="name"><a href="<?php echo get_permalink(); ?>" target="_self"><?php the_title(); ?></a></span>
                    </h3>
                    <?php $map = get_field('location_map'); ?>
                    <p class="card-text"><?php echo get_field('location_address_1', get_the_ID() ); ?><br/>
                        <?php echo ( get_field('location_address_2' ) ? get_field('location_address_2') . '<br/>' : ''); ?>
                        <?php echo get_field('location_city'); ?>, <?php echo get_field('location_state'); ?> <?php echo get_field('location_zip', get_the_ID()); ?><p/>
                        <a href="<?php echo get_permalink(); ?>" class="btn btn-primary" aria-label="Go to location page for <?php the_title(); ?>">View Location</a>
                        <?php if ($map) { ?>
                        <a class="btn btn-outline-primary" href="https://www.google.com/maps/dir/Current+Location/<?php echo $map['lat'] ?>,<?php echo $map['lng'] ?>" target="_blank">Get Directions</a>
                        <?php } ?>
                    </p>
                </div><!-- .card-body -->
            </div><!-- .card --> 
        <?php endwhile; 
            echo '</div></div></div>';
        else : ?>
        <p><?php _e( 'Sorry, no locations matched your criteria.' ); ?></p>
        <?php endif; 


            echo '</div>'; // .search-content
            echo '</div>'; // .container-fluid
            echo '</div>'; // .uams-module
            /* End Locations */
            wp_reset_query();
            /* Services */

            $post_type = array('services');

            echo '<div class="uams-module bg-auto">';
            echo '<div class="container-fluid">';
            echo '<div class="search-content row">';

            // accepts any wp_query args.
            $args = (array(
                's' => $s,
                'post_type' => $post_type,
                'posts_per_page' => 4,
                'order' => 'ASC',
                'orderby' => 'title',
            ));

            uamswp_custom_loop_base($args);

            if ( have_posts() ) : 
                echo '<div class="card-list-container"><div class="card-list card-list-doctors facetwp-template"><div class="inner-container content-width"><h2 class="post-type-heading">Services</h2>';
            while ( have_posts() ) : the_post(); 
            ?>
                <div class="card">
                    <?php if ( has_post_thumbnail() ) { ?>
                        <p>
                        <?php the_post_thumbnail('aspect-16-9-small', ['class' => 'img-responsive']); ?>
                        </p>
                    <?php } ?>
                    <?php $excerpt = get_the_excerpt(); ?>
                    <div class="card-body">
                        <h3 class="card-title">
                            <span class="name"><a href="<?php echo get_permalink(); ?>" target="_self"><?php the_title(); ?></a></span>
                        </h3>
                        <p class="card-text"><?php echo ( $excerpt ? wp_trim_words( $excerpt, 30, ' &hellip;' ) : wp_trim_words( wp_strip_all_tags( get_the_content(), 30, ' &hellip;' ) ) ); ?></p>
                            <a href="<?php echo get_permalink(); ?>" class="btn btn-primary stretched-link" aria-label="Go to Area of Expertise page for <?php the_title(); ?>">View Area of Expertise</a>
                    </div><!-- .card-body -->
                </div><!-- .card --> 
            <?php endwhile; 
                echo '</div></div></div>';
            else : ?>
            <?php  ?>
                <p><?php _e( 'Sorry, no services matched your criteria.' ); ?></p>
        <?php endif;


            echo '</div>'; // .search-content
            echo '</div>'; // .container-fluid
            echo '</div>'; // .uams-module

            /* End Services */
            wp_reset_query();
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

    add_filter( 'get_the_excerpt', 'strip_shortcodes', 20 );

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

genesis();
