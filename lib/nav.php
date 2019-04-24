<?php
/**
 * Navigation
 *
 * @package      Bootstrap for Genesis
 * @since        1.0
 * @link         http://webdevsuperfast.github.io
 * @author       Rotsen Mark Acob <webdevsuperfast.github.io>
 * @copyright    Copyright (c) 2015, Rotsen Mark Acob
 * @license      http://opensource.org/licenses/gpl-2.0.php GNU Public License
 *
*/

//if ( class_exists( 'UberMenuStandard' ) ) {
//    return;
//}

// remove primary & secondary nav from default position
//remove_action( 'genesis_after_header', 'genesis_do_nav' );
//add_action( 'genesis_header', 'genesis_do_nav' );

// filter menu args for bootstrap walker and other settings
add_filter( 'wp_nav_menu_args', 'uamswp_nav_menu_args_filter' );
function uamswp_nav_menu_args_filter( $args ) {

    require_once( UAMSWP_THEME_MODULES . 'class-wp-bootstrap-navwalker.php' );

    $menu_classes = array(
        'nav',
        'navbar-nav',
        'align-self-end'
    );

    $navextra = get_theme_mod( 'navextra', false );
    if ( $navextra !== '' ) {
        $menu_classes[] = 'mr-auto';
    } else {
        $menu_classes[] = 'ml-auto';
    }
    
    if ( 'primary' === $args['theme_location'] ) {
        $args['container'] = false;
        $args['menu_class'] = esc_attr( implode( ' ', $menu_classes ) );
        $args['fallback_cb'] = 'WP_Bootstrap_Navwalker::fallback';
        $args['walker'] = new WP_Bootstrap_Navwalker();
    }
    return $args;
}

// add bootstrap markup around the nav
add_filter( 'wp_nav_menu', 'uamswp_nav_menu_markup_filter', 10, 2 );
function uamswp_nav_menu_markup_filter( $html, $args ) {
    // only add additional Bootstrap markup to
    // primary and secondary nav locations
    if ( 'primary' !== $args->theme_location ) {
        return $html;
    }

    $data_target = 'nav' . sanitize_html_class( '-' . $args->theme_location );
    
    $output = '';

    // only include blog name and description in the nav
    // if it is the primary nav location
 //   if ( 'primary' === $args->theme_location ) {
 //       $output .= apply_filters( 'uamswp_navbar_brand', uamswp_navbar_brand_markup() );
 //   }

    $output .= '<nav class="site-nav navbar navbar-expand-sm">';
    //$output .= '<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#'.$data_target.'" aria-controls="'.$data_target.'" aria-expanded="false" aria-label="Toggle navigation"><span class="navbar-toggler-icon">Test</span></button>';
    $output .= '<div class="collapse navbar-collapse inner-container" id="'.$data_target.'">';
    $output .= $html;
    
    // $navextra = get_theme_mod( 'navextra', false );
    
    // if ( $navextra == true ) {
    //     $output .= apply_filters( 'uamswp_navbar_content', uamswp_navbar_content_markup() );
    // }

    $output .= '</div>'; // wrap
    $output .= '</nav>'; // navbar

    // $output .= '<style>.site-nav { border-bottom: 1px solid #eee; }</style>';
    
    return $output;
}

//* Navigation Extras
function uamswp_navbar_content_markup() {
    $url = get_home_url();
    
    $choices = get_theme_mod( 'navextra', 'search' );
    
    $output = '';
    
    switch( $choices ) {
        case 'search':
        default:
            $output .= '<form class="form-inline float-lg-right" method="get" action="'.$url.'" role="search">';
            $output .= '<input class="form-control mr-sm-2" type="text" placeholder="Search" name="s">';
            $output .= '<button class="btn btn-outline-success" type="submit">Search</button>';
            $output .= '</form>';
            break;
        case 'date': 
            $output .= '<p class="navbar-text navbar-right mb-0">';
            $output .= date_i18n( get_option( 'date_format' ) );
            $output .= '</p>';
            break;
        case '':
            $output .= '';
            break;
    }

	return $output;
}

//* Filter primary navigation output to match Bootstrap markup
// @link http://wordpress.stackexchange.com/questions/58377/using-a-filter-to-modify-genesis-wp-nav-menu/58394#58394
add_filter( 'genesis_do_nav', 'uamswp_override_do_nav', 10, 3 );
function uamswp_override_do_nav($nav_output, $nav, $args) {
    // return the modified result
    return sprintf( '%1$s', $nav );

}