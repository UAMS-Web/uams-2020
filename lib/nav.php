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


/**
 * Check if page or parent is a subsection
 *
 * @return string $id or false
 *
 * @since 1.0
 * @author Todd McKee
 */
function uamswp_nav_subsection(){
    $subsection = false;
    if (get_post_meta( get_the_id(), 'page_subsection', true)) {
        $id = get_the_id();
        $subsection = true;
    } else {
        $parents = get_post_ancestors(get_the_id());
        $parentsection = '';
        foreach($parents as $parent) {
            $parentsection = get_post_meta( $parent, 'page_subsection', true);
            if (! empty($parentsection) ) {
                $id = $parent;
                $subsection = true;
                break;
            }
        }
        
    }
    if ($subsection) {
        return $id;
    } else {
        return false;
    }
}
/**
 * 
 * Remove Primary Navigation if page or parent is subsection
 * 
 * @since 1.0
 * @author Todd McKee
 */
add_action( 'genesis_before', 'remove_primary_nav' );
function remove_primary_nav() {
	if ( uamswp_nav_subsection() || ! has_nav_menu( 'primary' ) ) {
		remove_action( 'genesis_after_header', 'genesis_do_nav' );
	}
}
/**
 * 
 * Add list_pages Primary Navigation if page or parent is subsection
 * 
 * @since 1.0
 * @author Todd McKee
 */
add_action( 'genesis_after_header', 'custom_nav_menu', 5 );
function custom_nav_menu() {
    $registered_nav_menus = get_registered_nav_menus();
	if ( uamswp_nav_subsection() ) {

        require_once( UAMSWP_THEME_MODULES . 'class-wp-bootstrap-pagewalker.php' );

        // Only run on pages
		if( ! is_page() )
            return;

        $args = array(
            'theme_location' => 'primary',
            'container'      => '',
            'menu'           => 'subsection-navigation', // !important! you need to give the name/slug of your menu
            // 'menu_class'     => $class,
            'echo'           => 0,
        );

        $nav = wp_nav_menu( $args );

        //* Do nothing if there is nothing to show
        if ( ! $nav )
            return;
        
        // Build a menu listing top level parent's children
        $args = array(
            'child_of' => uamswp_nav_subsection(),
            'title_li' => '',
            'echo'     => false,
            'walker'   => new WP_Bootstrap_Pagewalker(), // !important! create Bootstrap style navigation
        );
        
        
		$pagenav = wp_list_pages( $args );
		if( empty( $pagenav ) )
            return;
            
        // Add the appropriate navbar coding
        $wrapper_open  = '<nav class="site-nav navbar navbar-expand-sm">';
        //$wrapper_open .= '<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#genesis-nav-primary" aria-controls="genesis-nav-primary" aria-expanded="false" aria-label="Toggle navigation"><span class="navbar-toggler-icon">Test</span></button>';
        $wrapper_open .= '<div class="collapse navbar-collapse inner-container" id="genesis-nav-primary">';
        $wrapper_open .= '<ul id="menu-dropdowns" class="nav navbar-nav align-self-end mr-auto">';

        $wrapper_close  = '</ul>'; // ul
        $wrapper_close  = '</div>'; // wrap
        $wrapper_close .= '</nav>'; // navbar

        // Wrap the list items in an unordered list and navbar
        $pagenav = $wrapper_open . $pagenav . $wrapper_close;
    
        $pagenav_markup_open = genesis_markup( array(
            'html5'   => '<nav %s>',
            'xhtml'   => '<div id="pagenav">',
            'context' => 'genesis-nav-primary',
            'echo'    => false,
        ) );

        echo $pagenav;
        
	} elseif ( ! has_nav_menu( 'primary' ) || true !== has_nav_menu( 'primary' ) ) {

        require_once( UAMSWP_THEME_MODULES . 'class-wp-bootstrap-pagewalker.php' );

        $args = array(
            'theme_location' => 'primary',
            'container'      => '',
            'menu'           => 'page-navigation', // !important! you need to give the name/slug of your menu
            // 'menu_class'     => $class,
            'echo'           => false,
        );

        $nav = wp_nav_menu( $args );

        //* Do nothing if there is nothing to show
        // if ( ! $nav )
        //     return;
        function uamswp_wp_list_pages(){
            $excluded_pages = array();
            $all_pages = get_pages();
            foreach ( $all_pages as $the_page ) {
                $hide = get_post_meta($the_page->ID, 'page_hide_from_menu');
                if ( isset($hide[0]) && '1' == $hide ) {
                    $excluded_pages[] = $the_page->ID;
                }
            }
            $excluded_pages[] = get_option( 'page_on_front' );
            // Build a menu listing top level parent's children
            $args = array(
                'title_li' => '',
                'echo'     => false,
                'walker'   => new WP_Bootstrap_Pagewalker(), // !important! create Bootstrap style navigation
                'exclude' => implode(',',$excluded_pages),
            );
            return wp_list_pages( $args );
        }        
        
		$pagenav = uamswp_wp_list_pages(); //wp_list_pages( $args );
		if( empty( $pagenav ) )
            return;
            
        // Add the appropriate navbar coding
        $wrapper_open  = '<nav class="site-nav navbar navbar-expand-sm">';
        //$wrapper_open .= '<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#genesis-nav-primary" aria-controls="genesis-nav-primary" aria-expanded="false" aria-label="Toggle navigation"><span class="navbar-toggler-icon">Test</span></button>';
        $wrapper_open .= '<div class="collapse navbar-collapse inner-container" id="genesis-nav-primary">';
        $wrapper_open .= '<ul id="menu-dropdowns" class="nav navbar-nav align-self-end mr-auto">';

        $wrapper_close  = '</ul>'; // ul
        $wrapper_close  = '</div>'; // wrap
        $wrapper_close .= '</nav>'; // navbar

        // Wrap the list items in an unordered list and navbar
        $pagenav = $wrapper_open . $pagenav . $wrapper_close;
    
        $pagenav_markup_open = genesis_markup( array(
            'html5'   => '<nav %s>',
            'xhtml'   => '<div id="pagenav">',
            'context' => 'genesis-nav-primary',
            'echo'    => false,
        ) );

        echo $pagenav;
    }
}

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

    $data_target = 'genesis-nav' . sanitize_html_class( '-' . $args->theme_location );
    
    $output = '';

    $output .= '<nav class="site-nav navbar navbar-expand-sm" aria-label="Primary Navigation">';
    //$output .= '<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#'.$data_target.'" aria-controls="'.$data_target.'" aria-expanded="false" aria-label="Toggle navigation"><span class="navbar-toggler-icon">Test</span></button>';
    $output .= '<div class="collapse navbar-collapse inner-container" id="'.$data_target.'">';
    
    $navextra = get_theme_mod( 'navextra', false );
    
    if ( $navextra == true ) {
        $output .= apply_filters( 'uamswp_navbar_content', uamswp_navbar_content_markup() );
    }

    $output .= $html;

    $output .= '</div>'; // wrap
    $output .= '</nav>'; // navbar
    
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

remove_action( 'genesis_before_loop', 'genesis_do_breadcrumbs' );
add_action( 'genesis_after_header', 'genesis_do_breadcrumbs' );