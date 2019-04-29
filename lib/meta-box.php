<?php

/*
 *
 * Custom Meta box
 * 
 */

// Subsection Attribute
add_filter( 'rwmb_meta_boxes', 'your_prefix_register_meta_boxes' );
function your_prefix_register_meta_boxes( $meta_boxes ) {
	$meta_boxes[] = array (
		'title' => 'Page Settings',
		'id' => 'page-settings',
		'post_types' => array(
			'page',
		),
		'context' => 'side',
		'priority' => 'high',
		'status' => 'publish',
		'fields' => array(
			
			array (
				'id' => 'page_subsection',
				'name' => 'Subsection',
				'type' => 'checkbox',
				'desc' => 'Page is a subsection',
			),
		),
	);
	return $meta_boxes;
}