<?php
/**
 * Site Container Outer
 *
 * @package      Bootstrap for Genesis
 * @since        1.0
 * @link         http://webdevsuperfast.github.io
 * @author       Rotsen Mark Acob <webdevsuperfast.github.io>
 * @copyright    Copyright (c) 2015, Rotsen Mark Acob
 * @license      http://opensource.org/licenses/gpl-2.0.php GNU Public License
 *
*/

add_action( 'genesis_before', 'uamswp_sitecontainerouteropen', 5 );
add_action( 'genesis_after', 'uamswp_sitecontainerouterclose', 5 );

function uamswp_sitecontainerouteropen() {
    ?>
        <div id="site-container-outer">
    <?php 
}

function uamswp_sitecontainerouterclose() {
    ?>
        </div>
    <?php 
}
