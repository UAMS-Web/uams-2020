<?php
/**
 * Search Form
 *
 * @package      Bootstrap for Genesis
 * @since        1.0
 * @link         http://webdevsuperfast.github.io
 * @author       Rotsen Mark Acob <webdevsuperfast.github.io>
 * @copyright    Copyright (c) 2017, Rotsen Mark Acob
 * @license      http://opensource.org/licenses/gpl-2.0.php GNU Public License
 *
*/

add_filter( 'get_search_form', 'uamswp_search_form' );
function uamswp_search_form( $form ) {
    $form = '<form class="uams-search" role="search" method="get" id="searchform" action="' . home_url('/') . '" >
    <div class="input-group">
        <input type="search" class="form-control" id="uams-search-bar" value="' . get_search_query() . '" placeholder="' . esc_attr__('Search', 'uams-2020') . '..." name="s" autocomplete="off" aria-label="Enter search text">
        <div class="input-group-append">
            <button type="submit" id="searchsubmit" value="'. esc_attr__('Search', 'uams-2020') .'" class="btn btn-primary" title="Search"><span class="fas fa-search"></span><span class="sr-only">Search</span></button>
        </div>
    </div>';

    // Keep the post type filter when searching again from a filtered view.
    if ( ! empty( $_GET['type'] ) ) {
        $form .= '<input type="hidden" name="type" value="' . esc_attr( sanitize_key( wp_unslash( $_GET['type'] ) ) ) . '">';
    }

    $form .= '</form>';
    return $form;
}

// Keep search results pages out of search engine indexes.
add_filter( 'wp_robots', 'uamswp_search_robots' );
function uamswp_search_robots( $robots ) {
    if ( is_search() ) {
        $robots['noindex']   = true;
        $robots['noarchive'] = true;
        $robots['follow']    = true;
    }
    return $robots;
}

// Search results styles (previously printed inline in search.php).
add_action( 'wp_enqueue_scripts', 'uamswp_search_results_styles', 20 );
function uamswp_search_results_styles() {
    if ( ! is_search() ) {
        return;
    }
    wp_add_inline_style( 'app-css', '
        .search-link { font-size: small; color: blue; }
        .search-type {
            border: 1px solid transparent;
            border-radius: 3px;
            padding: .125em .25em;
            background-color: #DCDADB;
            font-size: .85em;
            text-align: center;
        }
        .search-count { margin-bottom: 1.5rem; }
    ' );
}

// UAMS Settings > Search: when to show the Google search of all UAMS sites.
add_action( 'acf/init', 'uamswp_search_settings_fields' );
function uamswp_search_settings_fields() {
    if ( ! function_exists( 'acf_add_local_field_group' ) ) {
        return;
    }

    acf_add_local_field_group( array(
        'key'    => 'group_uamswp_search_settings',
        'title'  => 'Search',
        'fields' => array(
            array(
                'key'           => 'field_uamswp_search_google_display',
                'label'         => 'Google search of all UAMS sites',
                'name'          => 'uamswp_search_google_display',
                'type'          => 'radio',
                'instructions'  => 'Shows a "Search All UAMS Sites" section with Google results for UAMS.edu and UAMShealth.com on the search results page.',
                'required'      => 0,
                'choices'       => array(
                    'fallback'   => 'Only when SearchWP is not available (default)',
                    'no_results' => 'When this site\'s search finds no results',
                    'always'     => 'Always, below this site\'s results',
                    'never'      => 'Never',
                ),
                'default_value' => 'fallback',
                'layout'        => 'vertical',
                'return_format' => 'value',
                'allow_null'    => 0,
                'other_choice'  => 0,
                'save_other_choice' => 0,
            ),
        ),
        'location' => array(
            array(
                array(
                    'param'    => 'options_page',
                    'operator' => '==',
                    'value'    => 'uamswp-settings',
                ),
            ),
        ),
        'menu_order'            => 50,
        'position'              => 'normal',
        'style'                 => 'default',
        'label_placement'       => 'top',
        'instruction_placement' => 'label',
        'active'                => true,
    ) );
}
