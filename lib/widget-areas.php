<?php
/**
 * Widget Areas
 *
 * @package      Bootstrap for Genesis
 * @since        1.0
 * @link         http://webdevsuperfast.github.io
 * @author       Rotsen Mark Acob <webdevsuperfast.github.io>
 * @copyright    Copyright (c) 2015, Rotsen Mark Acob
 * @license      http://opensource.org/licenses/gpl-2.0.php GNU Public License
 *
*/

// Register Footer Function
// add_action( 'init', 'uamswp_register_sidebars' );
// function uamswp_register_sidebars() {
// 	// Register Custom Sidebars
// 	genesis_register_sidebar( array(
// 		'id' => 'uams-footer',
// 		'name' => __( 'Footer', 'uams-2020' ),
// 		'description' => __( 'This is the footer widget area. Widgets will be displayed horizontal / full width.', 'uams-2020' )
// 	) );
// }

//* Unregister primary sidebar
// unregister_sidebar( 'sidebar' );
//* Unregister secondary sidebar
unregister_sidebar( 'sidebar-alt' );