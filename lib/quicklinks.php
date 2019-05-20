<?php
/**
 * Quicklinks
 *
 * @package      Bootstrap for Genesis
 * @since        1.0
 * @link         http://webdevsuperfast.github.io
 * @author       Rotsen Mark Acob <webdevsuperfast.github.io>
 * @copyright    Copyright (c) 2015, Rotsen Mark Acob
 * @license      http://opensource.org/licenses/gpl-2.0.php GNU Public License
 *
*/

add_action( 'genesis_before', 'uamswp_quicklinks', 5 );

function uamswp_quicklinks() { 
    ?>
        <nav id="sidebar">

            <div class="sidebar-header">
                <h3>Helpful Links</h3>
            </div>
            <?php uamswp_request_quicklinks() ?>
            <h5>Campus Links</h5>
            <ul class="list-unstyled">
                <li>
                    <a href="https://webmail.uams.edu" class="">Webmail</a>
                </li>
                <li>
                    <a href="http://www.uams.edu/IT" class="">Helpdesk / IT</a>
                </li>
            </ul>
        </nav>

        <?php 
}

function uamswp_request_quicklinks() {
    $request = wp_remote_get( 'http://acf.local/wp-json/menus/v2/quicklinks/' );
    if( is_wp_error( $request ) ) {
        return false; // Bail early
    }
    $body = wp_remote_retrieve_body( $request );
    $data = json_decode( $body );
    if( ! empty( $data ) ) {
        
        echo '<ul class="list-unstyled">';
        foreach( $data as $key => $menu_item ) {
            echo '<li>';
                echo '<a href="' . esc_url( $menu_item->url ) . '">' . $menu_item->title . '</a>';
            echo '</li>';
        }
        echo '</ul>';
    }

}
