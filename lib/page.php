<?php
/*
 *
 * Page options
 * 
 */

add_action( 'genesis_after_header', 'page_options', 5 );
function page_options() {
    $id = get_the_id();
    if ( get_field('page_title_options', $id) ) {
        $pageTitle = get_field('page_title_options', $id);
        if ('hidden' == $pageTitle) {
            // Hide Entry-header
            add_filter('genesis_attr_entry-header', 'uamswp_attributes_entry_header');
        } elseif ('graphic' == $pageTitle) {
            // Graphic Header
            // Remove original
            remove_action( 'genesis_entry_header', 'genesis_entry_header_markup_open', 5 );
            remove_action( 'genesis_entry_header', 'genesis_do_post_title' );
            remove_action( 'genesis_entry_header', 'genesis_entry_header_markup_close', 15 );
            // Add new location
            add_action( 'genesis_before_content', 'uamswp_graphic_title_wrap_open', 5 );
            add_action( 'genesis_before_content', 'genesis_entry_header_markup_open', 5 );
            add_action( 'genesis_before_content', 'uamswp_graphic_title_inner_1', 5 );
            add_action( 'genesis_before_content', 'genesis_do_post_title' );
            add_action( 'genesis_before_content', 'uamswp_graphic_title_inner_2', 15 );
            add_action( 'genesis_before_content', 'uamswp_graphic_title_lead_paragraph', 15 );
            add_action( 'genesis_before_content', 'uamswp_graphic_title_inner_3', 15 );
            add_action( 'genesis_before_content', 'genesis_entry_header_markup_close', 15 );
            add_action( 'genesis_before_content', 'uamswp_graphic_title_wrap_close', 15 );

            // Add relevant classes
            add_filter('genesis_attr_entry-header', 'uamswp_attributes_entry_header_graphic_title');

        } elseif ('hero' == $pageTitle) {
            // Hero
            // Remove original header
            remove_action( 'genesis_entry_header', 'genesis_entry_header_markup_open', 5 );
            remove_action( 'genesis_entry_header', 'genesis_do_post_title' );
            remove_action( 'genesis_entry_header', 'genesis_entry_header_markup_close', 15 );
            // Add new location for header
            add_action( 'genesis_before_content', 'genesis_entry_header_markup_open', 5 );
            add_action( 'genesis_before_content', 'genesis_do_post_title' );
            add_action( 'genesis_before_content', 'genesis_entry_header_markup_close', 15 );
            // Add SR-Only
            add_filter( 'genesis_attr_entry-header', 'uamswp_attributes_entry_header' );

            // Add hero section
            add_action( 'genesis_before_content', 'uamswp_page_hero', 20 );
        } else {
            // do nothing
        }
    }
}

function uamswp_attributes_entry_header($attributes)
{
	$attributes['class'] .= ' sr-only';
	
	return $attributes;
}

function uamswp_attributes_entry_header_graphic_title($attributes)
{
    if ( empty($page_cover_image) ) 
    $page_cover_image = get_field('page_cover_image');

    if ($page_cover_image) {
        $attributes['class'] .= ' uams-module extra-padding graphic-title bg-image bg-red';
    }
    else {
        $attributes['class'] .= ' uams-module extra-padding graphic-title bg-red';
    }
	
	return $attributes;
}

function uamswp_graphic_title_wrap_open()
{
    echo '<div class="col-12">';
}

function uamswp_graphic_title_inner_1()
{
    if ( empty($page_cover_image) ) 
    $page_cover_image = get_field('page_cover_image');
    
    if ($page_cover_image && function_exists( 'fly_add_image_size' ) ) {
        echo '<style>
        .entry-header:before {
            background-image: url("' . image_sizer($page_cover_image, 566, 216, 'center', 'center') . '");
        }

        /* XXS Breakpoint, retina */
        @media (-webkit-min-device-pixel-ratio: 2),
        (min-resolution: 192dpi) {
            .entry-header:before {
                background-image: url("' . image_sizer($page_cover_image, 1152, 432, 'center', 'center') . '");
            }
        }

        /* XS Breakpoint */
        @media (min-width: 576px) {
            .entry-header:before {
                background-image: url("' . image_sizer($page_cover_image, 768, 288, 'center', 'center') . '");
            }
        }

        /* XS Breakpoint, retina */
        @media (min-width: 576px) and (-webkit-min-device-pixel-ratio: 2),
        (min-width: 576px) and (min-resolution: 192dpi) {
            .entry-header:before {
                background-image: url("' . image_sizer($page_cover_image, 1536, 576, 'center', 'center') . '");
            }
        }

        /* SM Breakpoint */
        @media (min-width: 768px) {
            .entry-header:before {
                background-image: url("' . image_sizer($page_cover_image, 992, 372, 'center', 'center') . '");
            }
        }

        /* SM Breakpoint, retina */
        @media (min-width: 768px) and (-webkit-min-device-pixel-ratio: 2),
        (min-width: 768px) and (min-resolution: 192dpi) {
            .entry-header:before {
                background-image: url("' . image_sizer($page_cover_image, 1984, 744, 'center', 'center') . '");
            }
        }

        /* MD Breakpoint */
        @media (min-width: 992px) {
            .entry-header:before {
                background-image: url("' . image_sizer($page_cover_image, 1200, 450, 'center', 'center') . '");
            }
        }

        /* MD Breakpoint, retina */
        @media (min-width: 992px) and (-webkit-min-device-pixel-ratio: 2),
        (min-width: 992px) and (min-resolution: 192dpi) {
            .entry-header:before {
                background-image: url("' . image_sizer($page_cover_image, 2400, 900, 'center', 'center') . '");
            }
        }

        /* LG Breakpoint */
        @media (min-width: 1200px) {
            .entry-header:before {
                background-image: url("' . image_sizer($page_cover_image, 1500, 563, 'center', 'center') . '");
            }
        }

        /* LG Breakpoint, retina */
        @media (min-width: 1200px) and (-webkit-min-device-pixel-ratio: 2),
        (min-width: 1200px) and (min-resolution: 192dpi) {
            .entry-header:before {
                background-image: url("' . image_sizer($page_cover_image, 3000, 1125, 'center', 'center') . '");
            }
        }

        /* XL Breakpoint */
        @media (min-width: 1500px) {
            .entry-header:before {
                background-image: url("' . image_sizer($page_cover_image, 1921, 720, 'center', 'center') . '");
            }
        }

        /* XL Breakpoint, retina */
        @media (min-width: 1500px) and (-webkit-min-device-pixel-ratio: 2),
        (min-width: 1500px) and (min-resolution: 192dpi) {
            .entry-header:before {
                background-image: url("' . image_sizer($page_cover_image, 3842, 1441, 'center', 'center') . '");
            }
        }

        /* XXL Breakpoint */
        @media (min-width: 1921px) {
            .entry-header:before {
                background-image: url("' . image_sizer($page_cover_image, 2560, 960, 'center', 'center') . '");
            }
        }

        /* XXL Breakpoint, retina */
        @media (min-width: 1921px) and (-webkit-min-device-pixel-ratio: 2),
        (min-width: 1921px) and (min-resolution: 192dpi) {
            .entry-header:before {
                background-image: url("' . image_sizer($page_cover_image, 5120, 1920, 'center', 'center') . '");
            }
        }
    </style>';
    }
    elseif ($page_cover_image) {
        echo '<style>
            .entry-header:before {
                background-image: url("' . wp_get_attachment_url( $page_cover_image, 'full' ) . '");
            }
        </style>';
    }

    echo '<div class="text-container">
    <div class="graphic-title-heading">';
}

function uamswp_graphic_title_inner_2()
{
    echo '</div>';
}

function uamswp_graphic_title_lead_paragraph()
{
    if ( empty($page_description) ) 
    $page_description = get_field('page_description');

    if ($page_description) {
        echo '<div class="graphic-title-body"><p>';
        echo $page_description;
        echo '</p></div>';
    }
}

function uamswp_graphic_title_inner_3()
{
    echo '</div>';
}

function uamswp_graphic_title_wrap_close()
{
    echo '</div>';
}

function uamswp_page_hero() {
    $id = 'header';
    $hero_rows = get_field('page_hero')['hero'];
    echo '<div class="col-12">';
    include( get_stylesheet_directory() .'/blocks/hero.php' );
    echo '</div>';
}

/**
 * Check if page settings say breadcrumbs should be hidden.
 *
 * @return string $id or false
 *
 * @since 1.0
 * @author Josh Daugherty
 */
function uamswp_hide_breadcrumbs(){
    $hidebreadcrumbs = false;
    if (get_post_meta( get_the_id(), 'page_hide_breadcrumbs', true)) {
        $id = get_the_id();
        $hidebreadcrumbs = true;
    }
    if ($hidebreadcrumbs) {
        return $id;
    } else {
        return false;
    }
}

/**
 * 
 * Remove Breadcrumbs if page settings say breadcrumbs should be hidden.
 * 
 * @since 1.0
 * @author Josh Daugherty
 */
add_action( 'template_redirect', 'remove_breadcrumbs' );
function remove_breadcrumbs() {
	if ( uamswp_hide_breadcrumbs() ) {
		remove_action( 'genesis_after_header', 'genesis_do_breadcrumbs' );
	}
}

/**
 * Use h1 for all entry titles, linking if on archive page
 */
function uamswp_entry_title_h1( $title ) {
    $post_title = get_the_title( get_the_ID() );
    $post_link = get_the_permalink( get_the_ID() );
    if( is_archive() ) {
        $title = '<h1 class="entry-title" itemprop="headline"><a href="' . $post_link . '">' . $post_title . '</a></h1>';
    } else {
        $title = '<h1 class="entry-title" itemprop="headline">' . $post_title . '</h1>';
    }
    
    return $title;
}
add_filter( 'genesis_post_title_output', 'uamswp_entry_title_h1' );


// Customize the entry meta in the entry header (requires HTML5 theme support)
add_filter( 'genesis_post_info', 'uamswp_post_info_filter' );
function uamswp_post_info_filter($post_info) {
	if ( is_single() && 'post' == get_post_type() ) {
        $author_info = get_field('post_hide_author');
        if ( $author_info ) {
            $post_info = 'Posted on [post_date]';
        } else {
            $post_info = 'Posted by [post_author_posts_link] on [post_date]';
        }
		return $post_info;
	}
}

// Relocate post info
remove_action( 'genesis_entry_header', 'genesis_post_info', 12 );
add_action( 'genesis_entry_footer', 'genesis_post_info', 9 );

function uamswp_list_child_pages() {
	$hidechildmenu = false;
    if ((get_post_meta( get_the_id(), 'page_hide_child_menu', true) ) || ( 0 === count( get_pages('child_of=' . get_the_id())) ) ) { // If it's suppressed or none available, set to false
        $hidechildmenu = true;
    }
    if (!$hidechildmenu) {
        return uamswp_list_child_posts( 'page', 'Related Pages' );
    }
}
add_action('genesis_after_entry', 'uamswp_list_child_pages');