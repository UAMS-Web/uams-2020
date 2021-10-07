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

        case 'modules_action_bar':
            $id = $i;
            $heading = $module['action_bar_heading'];
            $background_color = $module['action_bar_background_color'];
            $action_bar_rows = $module['action_bar_section'];
            $geo = $module['action_bar_geo'];
            $geo_region = $module['action_bar_geo_region'];

            include( get_stylesheet_directory() .'/blocks/action-bar.php' );

            break;

        case 'modules_call_out':
            $id = $i;
            $heading = $module['call_out_heading'];
            $body = $module['call_out_body'];
            $use_image = $module['call_out_use_image'];
            $image = $module['call_out_image'];
            $background_color = $module['call_out_background_color'];
            $geo = $module['call_out_geo'];
            $geo_region = $module['call_out_geo_region'];

            include( get_stylesheet_directory() .'/blocks/call-out.php' );

            break;

        case 'modules_cta':
            $id = $i;
            $heading = $module['cta_bar_heading'];
            $body = $module['cta_bar_body'];
            $button_text = $module['cta_bar_button_text'];
            $button_url = $module['cta_bar_button_url'];
            if ($module['cta_bar_button_url']){
                $button_target = $module['cta_bar_button_url']['target'];
            }
            $button_desc = $module['cta_bar_button_description'];
            $layout = $module['cta_bar_layout'];
            $size = $module['cta_bar_size'];
            $use_image = $module['cta_bar_use_image'];
            $image = $module['cta_bar_image'];
            $background_color = $module['cta_bar_background_color'];
            $geo = $module['cta_bar_geo'];
            $geo_region = $module['cta_bar_geo_region'];

            include( get_stylesheet_directory() .'/blocks/cta.php' );

            break;

        case 'modules_hero':
            $id = $i;
            $hero_rows = $module['hero'];
            $geo = $module['hero_geo'];
            $geo_region = $module['hero_geo_region'];

            include( get_stylesheet_directory() .'/blocks/hero.php' );

            break;

        case 'modules_link_list':
            $id = $i;
            $heading = $module['link_list_heading'];
            // $hide_heading = $module['link_list_hide_heading'];
            $description = $module['link_list_description'];
            $background_color = $module['link_list_background_color'];
            // $link_list_icons = $module['link_list_icons'];
            $link_list_rows = $module['link_list_section'];
            $geo = $module['link_list_geo'];
            $geo_region = $module['link_list_geo_region'];

            include( get_stylesheet_directory() .'/blocks/link-list.php' );

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
            $image_group = $module['side_image']?: '';
            $image_postion = $module['side_image_position'] ?: 'left';
            $image_anchor = $module['side_image']['side_image_anchor'] ?: 'center';
            $background_color = $module['side_image_background_color'] ?: 'bg-white';
            $geo = $module['side_image_geo'];
            $geo_region = $module['side_image_geo_region'];

            include( get_stylesheet_directory() .'/blocks/image-side-by-side.php' );

            break;

        case 'modules_text_overlay':
            $id = $i;
            $overlay_rows = $module['overlay_section'];
            $geo = $module['overlay_geo'];
            $geo_region = $module['overlay_geo_region'];

            include( get_stylesheet_directory() .'/blocks/overlay.php' );

            break;

        case 'modules_post_tile':
            $id = $i;
            $heading = $module['post_tile_heading'];
            $hide_heading = $module['post_tile_hide_heading'];
            $background_color = $module['post_tile_background_color'];
            $category = $module['post_tile_category'];
            $post_button_text = $module['post_tile_post_button_text'] ?: 'Read the Story';
            $cat_button_text = $module['post_tile_category_button_text'] ?: 'View ' . $category->name . ' Archive';
            $geo = $module['post_tile_geo'];
            $geo_region = $module['post_tile_geo_region'];

            include( get_stylesheet_directory() .'/blocks/post-category-tile.php' );

            break;

        case 'modules_post_tiles':
            $id = $i;
            $heading = $module['post_tiles_heading'];
            $hide_heading = $module['post_tiles_hide_heading'];
            $background_color = $module['post_tiles_background_color'];
            $post_tiles_rows = $module['post_tiles_section'];
            $geo = $module['post_tiles_geo'];
            $geo_region = $module['post_tiles_geo_region'];

            include( get_stylesheet_directory() .'/blocks/post-category-tiles.php' );

            break;

        case 'modules_text_stacked':
            $id = $i;
            $heading = $module['stacked_heading'];
            $hide_heading = $module['stacked_hide_heading'];
            $description = $module['stacked_description'];
            $background_color = $module['stacked_background_color'];
            $stacked_rows = $module['stacked_section'];
            $more = $module['stacked_more'];
            $more_text = $module['stacked_more_text'];
            $more_button_text = $module['stacked_more_button_text'];
            $more_button_url = $module['stacked_more_button_url'];
            $more_button_description = $module['stacked_more_button_description'];
            $geo = $module['stacked_geo'];
            $geo_region = $module['stacked_geo_region'];

            include( get_stylesheet_directory() .'/blocks/stacked.php' );

            break; 

        case 'modules_news_grid':
            if (class_exists('UAMS_Syndicate_News_Base')) {
                $id = $i;
                $title = $module['news_title'];
                $hide_title = $module['news_hide_title'];
                $output = $module['news_format'];
                $category = $module['news_category'];
                $count = $module['news_count'];
                $offset = $module['news_offset'];
                $advancedCat = $module['news_advanced_cat'];
                $background_color = $module['news_background_color'];
                $hide_img = $module['news_hide_img'];
                $local = $module['news_local'];
                $link = $module['news_include_link'];
                $position = $module['news_position'];
                $articleID = $module['news_article_id'];
                $geo = $module['news_geo'];
                $geo_region = $module['news_geo_region'];
                $className = '';

                if ( 'grid' == $output ) {
                    $count = '3';
                } elseif ( 'cards' == $output ) {
                    $count = '4';
                } elseif ( 'full' == $output ) {
                    $count = '1';
                } elseif ( 'side' == $output ) {
                    $count = '1';
                }

                include( get_stylesheet_directory() .'/blocks/news.php' );

                // uamswp_module_header( $module );
                // // echo '<!--[uamswp_news output="'. $output .'"  news_title="'. $title .'"  hide_title="'. $hide_title .'" category="'. $category .'" count="'. $count .'" offset="'. $offset .'" advanced_cat="'. $advancedCat .'" local="'. $local .'" style="'. $background_color . $className .'" hide_img="'. $hide_img .'" include_link="'. $link .'" news_position="'. $position .'" id="'. $articleID .'"]-->';
                // // echo '<!-- '; print_r($geo); echo ' -->';
                // // echo '<!-- ' . do_shortcode( '[geot_debug]' ) . ' -->';
				// // GEO Logic
                // $geo_display = false;
                // if (!isset($geo)){
	            //     $geo_display = true;
                // } else {
	            //     if( $geo['geot_condition'] == 'include' ) {
				// 		if( geot_target_city( '', $geo['geot_city_regions'] ) ){
				// 			$geo_display = true;
				// 		}
				// 	}  else {
				// 		if ( geot_target_city( '', '', '', $geo['geot_city_regions'] ) ){
				// 			$geo_display = true;
				// 		}
				// 	}
				// }
				// if ($geo_display) {
				// 	echo do_shortcode('[uamswp_news output="'. $output .'"  news_title="'. $title .'"  hide_title="'. $hide_title .'" category="'. $category .'" count="'. $count .'" offset="'. $offset .'" advanced_cat="'. $advancedCat .'" local="'. $local .'" style="'. $background_color . $className .'" hide_img="'. $hide_img .'" include_link="'. $link .'" news_position="'. $position .'" id="'. $articleID .'"]' );
				// }
            }
            break;

        case 'modules_uams_gallery':
                $id = $i;
                $heading = $module['gallery_heading'];
                $hide_heading = $module['gallery_hide_heading'];
                $description = $module['gallery_description'];
                $gallery_columns = $module['gallery_columns'];
                $gallery_images = $module['gallery_images'];
                $background_color = $module['gallery_background_color'];
                $geo = $module['gallery_geo'];
                $geo_region = $module['gallery_geo_region'];
    
                include( get_stylesheet_directory() .'/blocks/gallery.php' );
    
                break;

        case 'modules_uams_content':
                    $id = $i;
                    $heading = $module['content_heading'];
                    $hide_heading = $module['content_hide_heading'];
                    $content_block = $module['content_content'];
                    $background_color = $module['content_background_color'];
                    $geo = $module['content_geo'];
                    $geo_region = $module['content_geo_region'];
        
                    include( get_stylesheet_directory() .'/blocks/content.php' );
        
                    break;

        case 'modules_uams_livewhale':
                    $id = $i;
                    $heading = $module['livewhale_heading'];
                    $livewhale = $module['livewhale_id'];
                    $background_color = $module['livewhale_background_color'];
                    $geo = $module['livewhale_geo'];
                    $geo_region = $module['livewhale_geo_region'];
        
                    include( get_stylesheet_directory() .'/blocks/livewhale.php' );
        
                    break;

        case 'modules_uams_section':
                    $id = $i;
                    $heading = $module['modules_uams_section_heading'];
                    $hide_heading = $module['modules_uams_section_hide_heading'];
                    $background_color = $module['modules_uams_section_background_color'];
                    $module_rows = $module['modules_uams_section_flexible_layout'];
        
                    if ( empty ($id) ) {
                        $id = !empty( $module['anchor_id'] ) ? sanitize_title_with_dashes( $module['anchor_id'] ) : 'module-' . ( $i + 1 );
                    } 
                    $id = 'uams-section-' . $id;  
                    ?>
                    <section class="uams-module section-block <?php echo $background_color; ?>" id="<?php echo $id; ?>" aria-label="<?php echo $heading; ?>">
                        <div class="container-fluid">
                            <div class="row">
                                <div class="col-12">
                                    <h2 class="module-title <?php echo $hide_heading ? " sr-only" : ""; ?>">
                                        <span class="title"><?php echo $heading; ?></span>
                                    </h2>
                                    <?php
                                    if( $module_rows ):
                                        //print_r($module_rows);
                                        echo '<div class="module-body">';
                                        foreach($module_rows as $module_row){
                                            if( $module_row['acf_fc_layout'] == 'uams_section_wysiwyg' ):
                                                echo $module_row['section_wysiwyg_html'];
                                            elseif($module_row['acf_fc_layout'] == 'modules_uams_section_youtube'):
                                                if( function_exists('lyte_preparse') && strpos($module_row['section_youtube_url'],"youtu") !== false ) {
                                                    echo '<div class="'. $module_row['section_youtube_width'] .'">';
                                                    echo lyte_parse( str_replace( 'https', 'httpv', $module_row['section_youtube_url'] ) ); 
                                                    echo '</div>';
                                                } else {
                                                    echo '<div class="'. $module_row['section_youtube_width'] .' wp-block-embed is-type-video embed-responsive wp-has-aspect-ratio embed-responsive-16by9">';
                                                    echo wp_oembed_get( $module_row['section_youtube_url'] ); 
                                                    echo '</div>';
                                                }
                                            endif;
                                        }
                                        echo '</div>';
                                    endif; 
                                    ?>
                                </div>
                            </div>
                        </div>
                    </section>
                    <?php
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
	// $classes = array( 'module' );
	// $classes[] = 'type-' . str_replace( '_', '-', $module['acf_fc_layout'] );
    // foreach ($module as $key => $value) {
    //     if (strpos($key, 'background_color') !== false) {
    //         $classes[] = $value;
    //         break;
    //     }
    // }
	// if( !empty( $module['bg_color'] ) )
	// 	$classes[] = 'bg-' . $module['bg_color'];
	// $id = !empty( $module['anchor_id'] ) ? sanitize_title_with_dashes( $module['anchor_id'] ) : 'module-' . ( $i + 1 );
	// echo '<section class="' . join( ' ', $classes ) . '" id="' . $id . '">';
	// echo '<div class="wrap">';
    echo '<!-- // Begin Module - '. $module['acf_fc_layout'] . '// -->';
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
	// echo '</div>';
	// echo '</section>';
    echo '<!-- // End Module - '. $module['acf_fc_layout'] .'// -->';
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

function display_call_out ($id, $className, $heading, $body, $use_image, $image, $background_color) {
    ?>
    
    <?php }