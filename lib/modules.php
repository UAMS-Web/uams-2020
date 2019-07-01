<?php
/**
 * Modules
 *
 * @package      UAMSWP
 * @author       Bill Erickson
 * @since        1.0.0
 * @license      GPL-2.0+
**/
/**
 * Display Modules
 *
 */
function uamswp_modules( $post_id = false ) {
	if( ! function_exists( 'get_field' ) )
		return;
	$post_id = $post_id ? intval( $post_id ) : get_the_ID();
	$modules = get_field( 'uamswp_modules', $post_id );
	if( empty( $modules ) )
		return;
	foreach( $modules as $i => $module )
		uamswp_module( $module, $i );
}
/**
 * Display Module
 *
 */
function uamswp_module( $module = array(), $i = false ) {
	if( empty( $module['acf_fc_layout'] ) )
		return;
	uamswp_module_open( $module, $i );
	switch( $module['acf_fc_layout'] ) {
		case uamswp_module_disable( $module ):
			break;
		// case 'content':
		// 	uamswp_module_header( $module );
		// 	echo '<div class="entry-content">' . apply_filters( 'uamswp_the_content', $module['content'] ) . '</div>';
        //     break;
        // case 'modules_heading':
		// 	uamswp_module_header( $module );
		// 	echo '<div class="entry-content">' . apply_filters( 'uamswp_the_content', $module['title'] ) . '</div>';
        //     break;
        case 'modules_image_side':
            // uamswp_module_header( $module ); // Used as section heading
            $id = $i;
            $layout = $module['side_text_layout'];
            $heading = $module['side_heading'];

            if ( $layout == 'body-only' ) {
                $body = $module['side_layout_body_text'];
            } else {
                $body = $module['side_layout_link_text'];
            }

            $link_list = $module['side_link_list'];
            $list_more = $module['side_link_list_include_more'];
            $cta = $module['side_cta'];
            $cta_text = $cta['side_cta_text'];
            $cta_link = $cta['side_cta_url'];
            $cta_target = $cta['side_cta_target'];
            $cta_desc = $cta['side_cta_description'];
            $image_group = $module['side_image'];
            $side_image = $image_group['side_image_image'];
            $image_alt = $image_group['side_image_alt_text'];
            $image_crop = $image_group['side_image_crop'];
            $image_postion = $module['side_image_position'] ?: 'left';
            $image_anchor = $module['side_image_anchor'] ?: 'center';
            $background_color = $module['side_image_background_color'] ?: 'bg-white';

            // echo $cta_link;
            // echo $cta_text;

            $image_alt = $image_alt ? $image_alt : get_post_meta($side_image, '_wp_attachment_image_alt', true);
            $cta_target = $cta_target ? ' target="blank"' : '';
            $cta_desc = $cta_desc ? ' aria-label="'.$cta_desc.'"' : '';
            $cta_link = $cta_link ? '<a class="btn btn-primary" href="'. $cta_link .'"' . $cta_desc . $cta_target . '>' : '';
            $side_image_width = wp_get_attachment_image_src($side_image, 'full')[1];

            // echo $cta_link;
            
            include( get_stylesheet_directory() .'/blocks/image-side-by-side.php' );
            
            break;

        case 'modules_action_bar':
            $id = $i;
            $heading = $module['action_bar_heading'];
            $background_color = $module['action_bar_background_color'];
            $action_bar_rows = $module['action_bar_section'];

            include( get_stylesheet_directory() .'/blocks/action-bar.php' );

            break;

        case 'modules_hero':
            $id = $i;
            $hero_rows = $module['hero'];

            include( get_stylesheet_directory() .'/blocks/hero.php' );

            break;

        case 'modules_post_tile':
            $id = $i;
            $heading = $module['post_tile_heading'];
            $hide_heading = $module['post_tile_hide_heading'];
            $background_color = $module['post_tile_background_color'];
            $category = $module['post_tile_category'];
            $post_button_text = $module['post_tile_post_button_text'] ?: 'Read the Story';
            $cat_button_text = $module['post_tile_category_button_text'] ?: 'View ' . $category->name . ' Archive';

            include( get_stylesheet_directory() .'/blocks/post-category-tile.php' );

            break;

        case 'modules_post_tiles':
            $id = $i;
            $heading = $module['post_tiles_heading'];
            $hide_heading = $module['post_tiles_hide_heading'];
            $background_color = $module['post_tiles_background_color'];
            $post_tiles_rows = $module['post_tiles_section'];

            include( get_stylesheet_directory() .'/blocks/post-category-tiles.php' );

            break;

        case 'modules_image_side':
            $id = $i;
            $layout = $module['side_text_layout'] ?: 'link-list';
            $heading = $module['side_heading'] ?: 'Heading goes here...';

            if ( $layout == 'body-only' ) {
                $body = $module['side_layout_body_text'] ?: 'This is where the body-only description goes';
            } else {
                $body = $module['side_layout_link_text'] ?: 'This is where the body + link list description goes';
            }

            $link_list = $module['side_link_list'] ?: '';
            $list_more = $module['side_link_include_more'] ?: '';
            $cta = $module['side_cta'] ?: '';
            $cta_text = $cta['side_cta_text'] ?: '';
            $cta_link = $cta['side_cta_url'] ?: '';
            $cta_target = $cta['side_cta_target'] ?: '';
            $cta_desc = $cta['side_cta_description'] ?: '';
            $image_group = $module['side_image']?: '';
            $side_image = $image_group['side_image_image'] ?: '';
            $image_alt = $image_group['side_image_alt_text'] ?: '';
            $image_crop = $image_group['side_image_crop'] ?: '';
            $image_postion = $module['side_image_position'] ?: 'left';
            $image_anchor = $module['side_image_anchor'] ?: 'center';
            $background_color = $module['side_image_background_color'] ?: 'bg-white';

            include( get_stylesheet_directory() .'/blocks/image-side-by-side.php' );

            break;

        case 'modules_text_stacked':
            $id = $i;
            $heading = $module['stacked_heading'];
            $hide_heading = $module['stacked_hide_heading'];
            $background_color = $module['stacked_background_color'];
            $stacked_rows = $module['stacked_section'];

            include( get_stylesheet_directory() .'/blocks/stacked.php' );

            break; 

        case 'modules_text_overlay':
            $id = $i;
            $overlay_rows = $module['overlay_section'];

            include( get_stylesheet_directory() .'/blocks/overlay.php' );

            break; 
		// More modules go here
	}
	uamswp_module_close( $module, $i );
}
/**
 * Module Open
 *
 */
function uamswp_module_open( $module, $i ) {
	if( uamswp_module_disable( $module ) )
		return;
	$classes = array( 'module' );
	$classes[] = 'type-' . str_replace( '_', '-', $module['acf_fc_layout'] );
	if( !empty( $module['bg_color'] ) )
		$classes[] = 'bg-' . $module['bg_color'];
	$id = !empty( $module['anchor_id'] ) ? sanitize_title_with_dashes( $module['anchor_id'] ) : 'module-' . ( $i + 1 );
	echo '<section class="' . join( ' ', $classes ) . '" id="' . $id . '">';
	echo '<div class="wrap">';
}
/**
 * Module Header
 *
 */
function uamswp_module_header( $module ) {
	if( !empty( $module['title'] ) ) {
		echo '<header><h3>' . esc_html( $module['title'] ) . '</h3></header>';
	}
}
/**
 * Module Close
 *
 */
function uamswp_module_close( $module, $i ) {
	if( uamswp_module_disable( $module ) )
		return;
	echo '</div>';
	echo '</section>';
}
/**
 * Module Disable
 *
 */
function uamswp_module_disable( $module ) {
	$disable = false;
	if( 'save_recipes_cta' == $module['acf_fc_layout'] && is_user_logged_in() )
		$disable = true;
	return $disable;
}
/**
 * Has Module
 *
 */
function uamswp_has_module( $module_to_find = '', $post_id = false ) {
	if( ! function_exists( 'get_field' ) )
		return;
	$post_id = $post_id ? intval( $post_id ) : get_the_ID();
	$modules = get_field( 'uamswp_modules', $post_id );
	$has_module = false;
	foreach( $modules as $module ) {
		if( $module_to_find == $module['acf_fc_layout'] )
			$has_module = true;
	}
	return $has_module;
}