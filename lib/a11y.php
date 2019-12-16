<?php 
/**
 *   Accessibility (A11y) fixes
 */

// Adding titles to iframes for accessibility.
// Originally from @bamadesigner
// https://github.com/wpcampus/wpcampus-network-plugin/commit/a48e4c81b1233a7051139046a91b3a19777ea902
add_filter( 'oembed_dataparse', 'filter_oembed_dataparse', 10, 3 );

/**
 * Filters the returned oEmbed HTML.
 *
 * @param   string - $return - The returned oEmbed HTML.
 * @param   object - $data - A data object result from an oEmbed provider.
 * @param   string - $url - The URL of the content to be embedded.
 *
 * @return  string - the HTML.
 */
function filter_oembed_dataparse( $return, $data, $url ) {
    // Get title from embed data to start.
    $title = ! empty( $data->title ) ? $data->title : '';
    // If no embed title, search the return markup for a title attribute.
    $preg_match     = '/title\=[\"|\\\']{1}([^\"\\\']*)[\"|\\\']{1}/i';
    $has_title_attr = preg_match( $preg_match, $return, $matches );
    if ( $has_title_attr && ! empty( $matches[1] ) ) {
        $title = $matches[1];
    }
    // Add embed type as title prefix.
    if ( $title && ! empty( $data->type ) ) {
        switch ( $data->type ) {
            // Capitalize first word.
            case 'video':
                $title = sprintf( __( '%s:', 'uamswp-network' ), ucfirst( $data->type ) ) . ' ' . $title;
                break;
        }
    }
    $title = apply_filters( 'uamswp_oembed_title', $title, $return, $data, $url );
    /*
        * If the title attribute already
        * exists, replace with new value.
        *
        * Otherwise, add the title attribute.
        */
    if ( $has_title_attr ) {
        $return = preg_replace( $preg_match, 'title="' . $title . '"', $return );
    } else {
        $return = preg_replace( '/^\<iframe/i', '<iframe title="' . $title . '"', $return );
    }

    $return = str_replace( 'frameborder="0"', '', $return ); // Quick strip of frameborder
    $return = preg_replace( ￼'(width\s*=\s*["\'](.*?)["\'])' ￼, '', $return ); // Standard height
    $return = preg_replace( ￼'(height\s*=\s*["\'](.*?)["\'])', '', $return ); // Standard width
    
    return $return;
}