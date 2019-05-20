<?php
/**
 * Quick Links
 *
 * @package      Bootstrap for Genesis
 * @since        1.0
 * @link         http://webdevsuperfast.github.io
 * @author       Rotsen Mark Acob <webdevsuperfast.github.io>
 * @copyright    Copyright (c) 2015, Rotsen Mark Acob
 * @license      http://opensource.org/licenses/gpl-2.0.php GNU Public License
 *
*/

add_action( 'genesis_after', 'uamswp_quicklinks', 4 );

function uamswp_quicklinks() { 
    ?>
        <nav id="quick-links" class="closed" aria-label="Quick Links">
            <button type="button" id="toggle-quick-links-inside" aria-controls="quick-links" aria-expanded="false" aria-label="Toggle Quick Links navigation">
                <span class="sr-only label">Expand Quick Links</span>
                <span class="fas fa-times fa-lg fa-fw"></span>
            </button>
            <h2 class="">Quick Links</h2>
            <?php uamswp_request_quicklinks() ?>
            <!-- <ul class="list-unstyled links links-large">
                <li><a href="javascript:void(0)"><span class="fas fa-fw fa-graduation-cap"></span>GUS</a></li>
                <li><a href="javascript:void(0)"><span class="fas fa-fw fa-users"></span>Profiles</a></li>
                <li><a href="javascript:void(0)"><span class="fas fa-fw fa-book"></span>Library</a></li>
                <li><a href="javascript:void(0)"><span class="fas fa-fw fa-shopping-cart"></span>UAMS Bookstore</a></li>
            </ul> -->
            <h3 class="h5">Helpful Links</h3>
            <ul class="list-unstyled links">
                <li><a href="javascript:void(0)"><span class="far fa-fw fa-envelope"></span>Webmail</a></li>
                <li><a href="javascript:void(0)"><span class="far fa-fw fa-id-card"></span>Employee Self Service</a></li>
                <li><a href="javascript:void(0)"><span class="fas fa-fw fa-desktop"></span>Computing / IT</a></li>
                <li><a href="javascript:void(0)"><span class="fas fa-fw fa-network-wired"></span>Intranet</a></li>
            </ul>
        </nav>
        <?php 
}

function uamswp_request_quicklinks() {
    $request = wp_remote_get( 'http://acf.local/wp-json/menus/v2/quicklinks/' );  // Dev URL
    if( is_wp_error( $request ) ) {
        return false; // Bail early
    }
    $body = wp_remote_retrieve_body( $request );
    $data = json_decode( $body );
    if( ! empty( $data ) ) {
        
        echo '<ul class="list-unstyled links links-large">';
        foreach( $data as $key => $menu_item ) {
            echo '<li>';
                echo '<a href="' . esc_url( $menu_item->url ) . '"><span class="'. implode( $menu_item->class, " " ) .'"></span>' . $menu_item->title . '</a>';
            echo '</li>';
        }
        echo '</ul>';
    }

}
