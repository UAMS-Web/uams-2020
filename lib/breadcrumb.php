<?php
/**
 * Breadcrumb
 *
 * @package      Bootstrap for Genesis
 * @since        1.0
 * @link         http://webdevsuperfast.github.io
 * @author       Rotsen Mark Acob <webdevsuperfast.github.io>
 * @copyright    Copyright (c) 2017, Rotsen Mark Acob
 * @license      http://opensource.org/licenses/gpl-2.0.php GNU Public License
 *
*/

// Changing breadcrumbs to Bootstrap's format
// @link http://jhtechservices.com/2015/05/integrate-bootstraps-breadcrumbs-into-genesis-theme/
add_filter( 'genesis_breadcrumb_args', 'uamswp_breadcrumb_args' );
function uamswp_breadcrumb_args( $args ){
	$args['sep'] = '     ';
	$args['prefix'] = sprintf( '<ol %s>', genesis_attr( 'breadcrumb' ) );
    $args['suffix'] = '</ol>';
    $args['labels']['prefix'] = '';
	$args['labels']['author'] = '';
	$args['labels']['category'] = ''; // Genesis 1.6 and later
	$args['labels']['tag'] = '';
	$args['labels']['date'] = '';
	$args['labels']['search'] = '';
	$args['labels']['tax'] = '';
	$args['labels']['post_type'] = '';
	$args['labels']['404'] = ''; // Genesis 1.5 and later
	return $args;
}

add_filter( 'genesis_build_crumbs', 'uamswp_build_crumbs', 10, 2 );
function uamswp_build_crumbs( $crumbs ){

	foreach( $crumbs as &$crumb ){
		$crumb = str_replace( '     ','</li><li class="breadcrumb-item active" itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem">', $crumb );
		$crumb = str_replace( '<a ','<a itemprop="item" ', $crumb );
		$class = strpos( $crumb, '</a>' ) ? 'class="breadcrumb-item"' : 'class="breadcrumb-item active"';
		$crumb = '<li ' . $class .' itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem">' . $crumb . '</li>';
		
	}

	return $crumbs;
}
