<?php
/**
 * Extras
 *
 * @package      Bootstrap for Genesis
 * @since        1.0
 * @link         http://webdevsuperfast.github.io
 * @author       Rotsen Mark Acob <webdevsuperfast.github.io>
 * @copyright    Copyright (c) 2017, Rotsen Mark Acob
 * @license      http://opensource.org/licenses/gpl-2.0.php GNU Public License
 *
*/
// Add class to images
// @link http://stackoverflow.com/a/22078964
add_filter( 'the_content', 'uamswp_image_responsive_class' );
function uamswp_image_responsive_class( $content ) {
   global $post;
   
   $pattern ="/<img(.*?)class=\"(.*?)\"(.*?)>/i";
   $replacement = '<img$1class="$2 img-fluid"$3>';
   $content = preg_replace( $pattern, $replacement, $content );
   
   return $content;
}

add_filter( 'body_class', 'page_blog_class' );
function page_blog_class( $classes ) {
    if ( is_page_template( 'page_blog.php' ) ) {
        $classes[] = 'blog';
    }

    return $classes;
}

// Remove Parentheses on Archive/Categories
// @link http://wordpress.stackexchange.com/questions/88545/how-to-remove-the-parentheses-from-the-category-widget
add_filter( 'wp_list_categories', 'uamswp_categories_postcount_filter', 10, 2 );
add_filter( 'get_archives_link', 'uamswp_categories_postcount_filter', 10, 2 );
function uamswp_categories_postcount_filter( $variable ) {
   $variable = str_replace( '(', '<span class="badge badge-pill badge-primary tag-default post-count">', $variable );
   $variable = str_replace( ')', '</span>', $variable );
   return $variable;
}

add_filter( 'the_password_form', function() {
    global $post;

    $label = 'pwbox-'.( empty( $post->ID ) ? rand() : $post->ID );

    $o = '<p>'.__( "To view this protected post, enter the password below:" ).'</p><form class="form-inline" action="' . esc_url( site_url( 'wp-login.php?action=postpass', 'login_post' ) ) . '" method="post"><label for="' . $label . '" class="sr-only">' . __( "Password:" ) . ' </label><input class="form-control mr-2"name="post_password" id="' . $label . '" type="password" size="20" maxlength="20" /><input type="submit" class="btn btn-primary" name="Submit" value="' . esc_attr__( 'Submit' ) . '" />
    </form>
    ';
    return $o;
} );

// Filter viewport meta values for Bootstrap
add_filter( 'genesis_viewport_value', 'uamswp_viewport_value' );
function uamswp_viewport_value() {
    return 'width=device-width, initial-scale=1, shrink-to-fit=no';
}

add_filter( 'genesis_register_widget_area_defaults', function( $defaults ) {
    global $wp_registered_sidebars;
    global $wp_widget_factory;
    // $test = $wp_widget_factory->widgets['WP_Widget_Recent_Posts'];

    // if ( isset( $wp_registered_sidebars['sidebar'] ) ) {
        $defaults = array(
            'before_widget' => genesis_markup( array(
                'open'    => '<section id="%%1$s" class="widget %%2$s">',
                'context' => 'widget-wrap',
                'echo'    => false,
            ) ),
            'after_widget'  => genesis_markup( array(
                'close'   => '</section>' . '\n',
                'context' => 'widget-wrap',
                'echo'    => false
            ) ),
            'before_title'  => '<h4 class="widget-title widgettitle">',
            'after_title'   => '</h4><div class="widget-wrap">',
        );
    // }

    return $defaults;
} );

add_filter( 'wp_link_pages_args', function( $params ) {
    $params['before'] = '<ul class="post-pagination">';
    $params['after'] = '</ul>';
    return $params;
} );

add_filter( 'wp_link_pages_link', function( $link ) {
    // var_dump( $link[1] );
    if ( $link && 'a' !== $link[1] ) {
        $link = '<li class="page-item active"><a href="#">' . $link . '</a></li>';
    } else {
        $link = '<li class="page-item">' . $link . '</li>';
    }
    return $link;
} );

add_filter( 'genesis_pre_get_option_footer_text', function( $creds ) {
    if ( get_theme_mod( 'creds', false ) ) {
        $creds = get_theme_mod( 'creds' );
    }

    return $creds;
} );

// Remove P tags wrapping on images
add_filter( 'the_content', 'uamswp_filter_ptags_on_images' );
function uamswp_filter_ptags_on_images( $content ) {
	return preg_replace( '/<p>\s*(<a .*>)?\s*(<img .* \/>)\s*(\/a>)?\s*<\/p>/iU', '\1\2\3', $content );
}

// Add aria-label for download button
add_filter( 'the_content', 'uamswp_filter_download_button' );
function uamswp_filter_download_button( $content ) {
    $preg_match = '/<div class="wp-block-file"><a .*>([^<]*)<\/a><a(.*)/iU';
    $has_download_button = preg_match( $preg_match, $content, $matches );
    if ( $has_download_button && ! empty( $matches[1] ) ) {
        $content = preg_replace_callback( $preg_match, 'add_download_title', $content );
    }
    return $content;
}
function add_download_title( $matches ) {
    // matches[0] = <div class="wp-block-file"><a href="">File Name</a><a 
    // matches[1] = File Name
    // matches[2] = href="" class="wp-block-file__button" download>Download</a></div>
    return $matches[0] . ' aria-label="Download for '. $matches[1] .'"' . $matches[2];
}

// Replace custom logo class to bootstrap
add_filter( 'get_custom_logo', function( $html ) {
    $html = str_replace( 'custom-logo-link', 'navbar-brand', $html );

    return $html;
}, 10 );

function uamswp_title($html) { 
    // Get site information
    $site = uams_get_site_info()['site'];
    $subsite = uams_get_site_info()['subsite'];

    $pagetitle = get_the_title();
    if ( is_search() ) {
        $pagetitle = 'Search Results';
    }
    if ( is_archive() ) {
        $post_type = get_post_type( get_the_id() );
        $post_type_object = get_post_type_object( $post_type );
        $pagetitle = $post_type_object->label;
    }
    if ( is_archive() && (is_category() || is_tag() || is_tax()) ) {
        $pagetitle = single_term_title("", false);
    }
    // Check if seopress title is set and use it for page title
    $seopress_title = get_post_meta( get_the_id(), '_seopress_titles_title', true ) ?: "";
    if (!empty($seopress_title) ) {
        $pagetitle = $seopress_title;
    }
    // Replace three spaces in sitename with one
    $sitename = str_replace('   ', ' ', get_bloginfo( "name" ));
    // Multisite - get the base multisite name
    $sitehome = str_replace('   ', ' ', get_blog_details(1)->blogname);
    // Set uams_ versions as the same for default
    $uams_sitename = $sitename;
    $uams_sitehome = $sitehome;

    // Prepend UAMS to sitename & sitehome as uams_ versions. Used for Institutes and potential others
    if (strpos("UAMS", $sitename) === false && strpos("University of Arkansas for Medical Sciences",$sitename) === false) {
       $uams_sitename = 'UAMS ' . $sitename; 
    }
    if (strpos("UAMS", $sitehome) === false && strpos("University of Arkansas for Medical Sciences",$sitehome) === false) {
        $uams_sitehome = 'UAMS ' . $sitehome; 
    } 

    // if( 'uams' == $site && 'main' == $subsite && is_home() ) {
    //     // Main UAMS site homepage
    //     $html = 'University of Arkansas for Medical Sciences (UAMS)';
    // } elseif ( 'uams' == $site && 'main' == $subsite ) {
    //     $html = $pagetitle . ' | University of Arkansas for Medical Sciences';
    // } elseif ( 'uams' == $site && 'main' != $subsite && is_home() ) {
    //     $html = $sitename;
    // } elseif ( 'uams' == $site && 'main' != $subsite ) {
    //     $html = $pagetitle . ' | ' . $sitename;
    // } elseif ( 'uamshealth' == $site && 'main' == $subsite && is_home() ) { 
    //     // Main UAMS Health site homepage
    //     $html = 'UAMS Health';
    // } elseif ( 'uamshealth' == $site && 'main' == $subsite ) { 
    //     $html = $pagetitle . ' | UAMS Health';
    // } elseif ( 'uamshealth' == $site && 'main' != $subsite && is_home() ) { 
    //     $html = $sitename . ' | UAMS Health';
    // } elseif ( 'uamshealth' == $site && 'main' != $subsite ) {
    //     $html = $pagetitle . ' | UAMS Health';
    // } elseif ( 'inside' == $site && 'main' == $subsite && is_home() ) { 
    //     // Main Inside site homepage
    //     $html = 'Inside UAMS';
    // } elseif ( 'inside' == $site && 'main' == $subsite ) { 
    //     $html = $pagetitle . ' | Inside UAMS';
    // } elseif ( 'inside' == $site && 'main' != $subsite && is_home() ) { 
    //     $html = $sitename . ' | Inside UAMS';
    // } elseif ( 'inside' == $site && 'main' != $subsite ) {
    //     $html = $pagetitle . ' | ' . $sitename;
    // } else {
    //     $html = $pagetitle . ' | ' . $sitename;
    // }

    //Re-org based onn breadcrumbs
    $page_title_404 = 'Page Not Found';
    if ( 'uamshealth' == uams_get_site_info()['site'] ) {
		if ( ( 'main' == uams_get_site_info()['subsite']) ) {
            if ( is_home() || is_front_page() ) {
                $html = 'UAMS Health';
            } else { 
                $html = ( is_404() ? $page_title_404 : $pagetitle ) . ' | UAMS Health';
            }
        } elseif( ( 'dept' == uams_get_site_info()['subsite']) ) {
            if ( is_home() || is_front_page() ) {
                $html = $sitename . ' | UAMS Health';
            } else {
                $html = ( is_404() ? $page_title_404 : $pagetitle ) . ' | ' . $uams_sitename;
            }
		} else {
            if ( is_home() || is_front_page() ) {
                $html = $sitename . ' | UAMS Health';
            } else {
                $html = ( is_404() ? $page_title_404 : $pagetitle ) . ' | UAMS Health';
            }
		}
	} elseif ( ('inside' == uams_get_site_info()['site'] ) ) {
		if ( ( 'main' == uams_get_site_info()['subsite']) ) {
			if ( is_home() || is_front_page() ) {
                $html = 'Inside UAMS';
            } else { 
                $html = ( is_404() ? $page_title_404 : $pagetitle ) . ' | Inside UAMS';
            }
        } elseif( ( 'none' != uams_get_site_info()['subsite']) ) {
            if ( is_home() || is_front_page() ) {
                $html = $sitename . ' | Inside UAMS';
            } else {
                $html = ( is_404() ? $page_title_404 : $pagetitle ) . ' | ' . $uams_sitename;
            }
		} else {
			if ( is_home() || is_front_page() ) {
                $html = $sitename . ' | Inside UAMS';
            } else {
                $html = ( is_404() ? $page_title_404 : $pagetitle ) . ' | Inside UAMS';
            }
		}
	} elseif ( ('institute' == uams_get_site_info()['site'] ) ) {
		if ( ( 'main' == uams_get_site_info()['department']) ) {
			if ( is_home() || is_front_page() ) {
                $html = $uams_sitename;
            } else {
                $html = ( is_404() ? $page_title_404 : $pagetitle ) . ' | ' . $uams_sitename;
            }
        } elseif( ( 'dept' == uams_get_site_info()['department']) ) { // Dept / org unit
            if ( is_home() || is_front_page() ) {
                $html = $sitename . ' | ' . $uams_sitehome;
            } else {
                $html = ( is_404() ? $page_title_404 : $pagetitle ) . ' | ' . $uams_sitename;
            }
		} else { // Not an org unit
			if ( is_home() || is_front_page() ) {
                $html = $sitename . ' | ' . $uams_sitehome;
            } else {
                $html = ( is_404() ? $page_title_404 : $pagetitle ) . ' | ' . $uams_sitehome;
            }
		}
    } else { // Site == uams
        if ( 'main' != uams_get_site_info()['subsite'] && 'none' != uams_get_site_info()['subsite'] ) { // NW & Regional Campus
            if ( ( 'main' == uams_get_site_info()['department']) || ( '' == uams_get_site_info()['department']) ) {
                if ( is_home() || is_front_page() ) {
                    $html = $uams_sitename;
                } else {
                    $html = ( is_404() ? $page_title_404 : $pagetitle ) . ' | ' . $uams_sitename;
                }
            } elseif( ( 'none' != uams_get_site_info()['department'] ) ) { // Dept / org unit
                if ( is_home() || is_front_page() ) {
                    $html = $sitename . ' | ' . $uams_sitehome;
                } else {
                    $html = ( is_404() ? $page_title_404 : $pagetitle ) . ' | ' . $uams_sitename;
                }
            } else { // Not an org unit
                if ( is_home() || is_front_page() ) {
                    $html = $sitename . ' | ' . $uams_sitehome;
                } else {
                    $html = ( is_404() ? $page_title_404 : $pagetitle ) . ' | ' . $uams_sitehome;
                }
            }
        } elseif ( 'main' == uams_get_site_info()['subsite'] ) {
            if ( is_home() || is_front_page() ) {
                $html = 'University of Arkansas for Medical Sciences (UAMS)';
            } else {
                $html = ( is_404() ? $page_title_404 : $pagetitle ) . ' | UAMS';
            }
        // } elseif ('none' == uams_get_site_info()['subsite'] ) { // Option if needed in the future
        //         if ( is_home() || is_front_page() ) {
        //             $html = $sitename . ' | UAMS';
        //         } else {
        //             $html = ( is_404() ? $page_title_404 : $pagetitle ) . ' | UAMS';
        //         }
        } elseif ('main' != uams_get_site_info()['department'] && '' != uams_get_site_info()['department'] && 'uams' != uams_get_site_info()['department'] ) {
            if( ( 'none' != uams_get_site_info()['department']) ) { // Dept / org unit
                if ( is_home() || is_front_page() ) {
                    $html = $sitename . ' | ' . $uams_sitehome;
                } else {
                    $html = ( is_404() ? $page_title_404 : $pagetitle ) . ' | ' . $sitename;
                }
            } else { // Not an org unit
                if ( is_home() || is_front_page() ) {
                    $html = $sitename . ' | ' . $uams_sitehome;
                } else {
                    $html = ( is_404() ? $page_title_404 : $pagetitle ) . ' | ' . $uams_sitehome;
                }
            }
        } else { // Default Fallback
            if ( is_home() || is_front_page() ) {
                $html = $sitename;
            } else {
                $html = ( is_404() ? $page_title_404 : $pagetitle ) . ' | ' . $sitename;
            } 
        }
	}
    
    return $html;
}
add_filter('seopress_titles_title', 'uamswp_title', 10, 2);
