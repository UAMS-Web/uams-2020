<?php

/*
 *
 * Custom ACF Blocks
 * 
 */

add_action('acf/init', 'uams_register_blocks');
function uams_register_blocks() {

    // check function exists.
    if( function_exists('acf_register_block_type') ) {

        acf_register_block_type(array(
            'name'              => 'action-bar',
            'title'             => __('UAMS Action Bar'),
            'description'       => __('Action Bar.'),
            'category'          => 'common',
            'icon'              => 'admin-links',
            'keywords'          => array('uams', 'action bar', 'links'),
            'mode'              => 'auto',
            'align'             => 'full',
            'render_template'   => 'blocks/action-bar.php',
        ));
        acf_register_block_type(array(
            'name'              => 'call-out',
            'title'             => __('UAMS Call-Out'),
            'description'       => __('Call-Out.'),
            'category'          => 'common',
            'icon'              => 'megaphone',
            'keywords'          => array('uams', 'callout', 'call-out', 'text'),
            'mode'              => 'auto',
            'align'             => 'full',
            'render_template'   => 'blocks/call-out.php',
        ));
        acf_register_block_type(array(
            'name'              => 'cta',
            'title'             => __('UAMS CTA Bar'),
            'description'       => __('Call-to-Action (CTA) Bar.'),
            'category'          => 'common',
            'icon'              => 'format-status',
            'keywords'          => array('uams', 'cta', 'call-to-action', 'call to action', 'button'),
            'mode'              => 'auto',
            'align'             => 'full',
            'render_template'   => 'blocks/cta.php',
        ));
        acf_register_block_type(array(
            'name'              => 'hero',
            'title'             => __('UAMS Hero'),
            'description'       => __('UAMS Hero / Slideshow.'),
            'category'          => 'common',
            'icon'              => 'images-alt2',
            'keywords'          => array('uams', 'slides', 'slideshow', 'hero'),
            'mode'              => 'auto',
            'align'             => 'full',
            'render_template'   => 'blocks/hero.php',
        ));
        acf_register_block_type(array(
            'name'              => 'link-list',
            'title'             => __('UAMS Link List'),
            'description'       => __('A list of linked tiles.'),
            'category'          => 'common',
            'icon'              => 'admin-links',
            'keywords'          => array('uams', 'link', 'links', 'list'),
            'mode'              => 'auto',
            'align'             => 'full',
            'render_template'   => 'blocks/link-list.php',
        ));
        if (class_exists('UAMS_Syndicate_News_Base')) { // Add block if news syndication plugin is active
            acf_register_block_type(array(
                'name'              => 'uams-news',
                'title'             => __('UAMS News'),
                'description'       => __('UAMS News Syndication'),
                'category'          => 'common',
                'icon'              => 'rss',
                'keywords'          => array('uams', 'news', 'syndication'),
                'mode'              => 'auto',
                'align'             => 'full',
                'render_template'   => 'blocks/news.php',
            ));
        }
        acf_register_block_type(array(
            'name'              => 'text-overlay',
            'title'             => __('UAMS Text & Image Overlay'),
            'description'       => __('Text and a button on top of an image.'),
            'category'          => 'common',
            'icon'              => 'format-image',
            'keywords'          => array('uams', 'text', 'image', 'overlay'),
            'mode'              => 'auto',
            'align'             => 'full',
            'render_template'   => 'blocks/overlay.php',
        ));
        acf_register_block_type(array(
            'name'              => 'post-category-tile',
            'title'             => __('UAMS Post Category Tile (Single)'),
            'description'       => __('One tile displaying a post from an individual post category.'),
            'category'          => 'common',
            'icon'              => 'screenoptions',
            'keywords'          => array('uams', 'news', 'posts', 'post', 'articles', 'article', 'link', 'links', 'intranet', 'inside', 'tile', 'tiles', 'sidebar', 'side bar'),
            'mode'              => 'auto',
            'align'             => 'full',
            'render_template'   => 'blocks/post-category-tile.php',
        ));
        acf_register_block_type(array(
            'name'              => 'post-category-tiles',
            'title'             => __('UAMS Post Category Tile (Double)'),
            'description'       => __('Two tiles displaying posts from individual post categories.'),
            'category'          => 'common',
            'icon'              => 'screenoptions',
            'keywords'          => array('uams', 'news', 'posts', 'post', 'articles', 'article', 'link', 'links', 'intranet', 'inside', 'tile', 'tiles', 'sidebar', 'side bar'),
            'mode'              => 'auto',
            'align'             => 'full',
            'render_template'   => 'blocks/post-category-tiles.php',
        ));
        acf_register_block_type(array(
            'name'              => 'image-side',
            'title'             => __('UAMS Side-by-Side Image & Text'),
            'description'       => __('Image on one side, text on the other side.'),
            'category'          => 'common',
            'icon'              => 'id',
            'keywords'          => array('uams', 'text', 'image', 'side'),
            'mode'              => 'auto',
            'align'             => 'full',
            'render_template'   => 'blocks/image-side-by-side.php',
        ));
        acf_register_block_type(array(
            'name'              => 'text-stacked',
            'title'             => __('UAMS Stacked Image & Text'),
            'description'       => __('Stacked Image & Text'),
            'category'          => 'common',
            'icon'              => 'screenoptions',
            'keywords'          => array('uams', 'text', 'image', 'stack', 'stacked'),
            'mode'              => 'auto',
            'align'             => 'full',
            'render_template'   => 'blocks/stacked.php',
        ));
        acf_register_block_type(array(
            'name'              => 'livewhale-calendar',
            'title'             => __('UAMS LiveWhale Calendar'),
            'description'       => __('LiveWhale widget'),
            'category'          => 'common',
            'icon'              => 'calendar-alt',
            'keywords'          => array('uams', 'calendar', 'livewhale'),
            'mode'              => 'auto',
            'align'             => 'full',
            'render_template'   => 'blocks/livewhale.php',
        ));
        acf_register_block_type(array(
            'name'              => 'uams-gallery',
            'title'             => __('UAMS Gallery'),
            'description'       => __('Custom Gallery with lightbox'),
            'category'          => 'common',
            'icon'              => 'format-gallery',
            'keywords'          => array('uams', 'gallery'),
            'mode'              => 'auto',
            'align'             => 'full',
            'render_template'   => 'blocks/gallery.php',
		));
        acf_register_block_type(array(
            'name'              => 'uams-content',
            'title'             => __('UAMS Content'),
            'description'       => __('Basic text content'),
            'category'          => 'common',
            'icon'              => 'editor-alignleft',
            'keywords'          => array('uams', 'content'),
            'mode'              => 'auto',
            'align'             => 'full',
            'render_template'   => 'blocks/content.php',
		));
        // acf_register_block_type(array(
        //     'name'              => 'block',
        //     'title'             => __('UAMS Block'),
        //     'description'       => __('A custom hero block.'),
        //     'category'          => 'common',
        //     'icon'              => '',
        //     'keywords'          => array('uams', 'block'),
        //     'mode'              => 'auto',
        //     'align'             => 'full',
        //     'render_template'   => 'blocks/block.php',
		// ));
    }
}

if( function_exists('acf_add_local_field_group') ):

    // Use single source
    $suffix = '_b'; // Blocks
    $action_bar = require( get_stylesheet_directory() .'/acf_fields/action-bar.php' );
    $call_out = require( get_stylesheet_directory() .'/acf_fields/call-out.php' );
    $cta = require( get_stylesheet_directory() .'/acf_fields/cta.php' );
    $hero = require( get_stylesheet_directory() .'/acf_fields/hero.php' );
    $link_list = require( get_stylesheet_directory() .'/acf_fields/link-list.php' );
    $news = require( get_stylesheet_directory() .'/acf_fields/news.php' );
    $overlay = require( get_stylesheet_directory() .'/acf_fields/overlay.php' );
    $post_tile = require( get_stylesheet_directory() .'/acf_fields/post-category-tile.php' );
    $post_tiles = require( get_stylesheet_directory() .'/acf_fields/post-category-tiles.php' );
    $side_by_side = require( get_stylesheet_directory() .'/acf_fields/image-side-by-side.php' );
    $stacked = require( get_stylesheet_directory() .'/acf_fields/stacked.php' );
    $livewhale = require( get_stylesheet_directory() .'/acf_fields/livewhale.php' );
    $gallery = require( get_stylesheet_directory() .'/acf_fields/gallery.php' );
    $content = require( get_stylesheet_directory() .'/acf_fields/content.php' );
    

    // Add local field group for UAMS Action Bar Block
    acf_add_local_field_group(array(
        'key' => 'group_5cf9847426451',
        'title' => 'Block: UAMS Action Bar',
        'fields' => $action_bar,
        'location' => array(
            array(
                array(
                    'param' => 'block',
                    'operator' => '==',
                    'value' => 'acf/action-bar',
                ),
            ),
        ),
        'menu_order' => 0,
        'position' => 'normal',
        'style' => 'default',
        'label_placement' => 'top',
        'instruction_placement' => 'label',
        'hide_on_screen' => '',
        'active' => true,
        'description' => '',
    ));

    // Add local field group for UAMS Call-Out Block
    acf_add_local_field_group(array(
        'key' => 'group_5cf980995ac56',
        'title' => 'Block: UAMS Call-Out',
        'fields' => $call_out,
        'location' => array(
            array(
                array(
                    'param' => 'block',
                    'operator' => '==',
                    'value' => 'acf/call-out',
                ),
            ),
            array(
                array(
                    'param' => 'widget',
                    'operator' => '==',
                    'value' => 'uamswp_callout_widget',
                ),
            ),
        ),
        'menu_order' => 0,
        'position' => 'normal',
        'style' => 'default',
        'label_placement' => 'top',
        'instruction_placement' => 'label',
        'hide_on_screen' => '',
        'active' => true,
        'description' => '',
    ));

    // Add local field group for UAMS CTA Bar Block
    acf_add_local_field_group(array(
        'key' => 'group_5cf938222421c',
        'title' => 'Block: UAMS CTA Bar',
        'fields' => $cta,
        'location' => array(
            array(
                array(
                    'param' => 'block',
                    'operator' => '==',
                    'value' => 'acf/cta',
                ),
            ),
            array(
                array(
                    'param' => 'widget',
                    'operator' => '==',
                    'value' => 'uamswp_cta_widget',
                ),
            ),
        ),
        'menu_order' => 0,
        'position' => 'normal',
        'style' => 'default',
        'label_placement' => 'top',
        'instruction_placement' => 'label',
        'hide_on_screen' => '',
        'active' => true,
        'description' => '',
    ));

    // Add local field group for UAMS Hero Block
    acf_add_local_field_group(array(
        'key' => 'group_5ceef46c9fe82',
        'title' => 'Block: UAMS Hero',
        'fields' => $hero,
        'location' => array(
            array(
                array(
                    'param' => 'block',
                    'operator' => '==',
                    'value' => 'acf/hero',
                ),
            ),
        ),
        'menu_order' => 0,
        'position' => 'normal',
        'style' => 'default',
        'label_placement' => 'left',
        'instruction_placement' => 'label',
        'hide_on_screen' => '',
        'active' => true,
        'description' => '',
    ));

    // Add local field group for UAMS Link List Block
    acf_add_local_field_group(array(
        'key' => 'group_uams_link_list',
        'title' => 'Block: UAMS Link List',
        'fields' => $link_list,
        'location' => array(
            array(
                array(
                    'param' => 'block',
                    'operator' => '==',
                    'value' => 'acf/link-list',
                ),
            ),
        ),
        'menu_order' => 0,
        'position' => 'normal',
        'style' => 'default',
        'label_placement' => 'top',
        'instruction_placement' => 'label',
        'hide_on_screen' => '',
        'active' => true,
        'description' => '',
    ));

    // Add local field group for UAMS News Block
    acf_add_local_field_group(array(
        'key' => 'group_uams_news',
        'title' => 'Block: UAMS News',
        'fields' => $news,
        'location' => array(
            array(
                array(
                    'param' => 'block',
                    'operator' => '==',
                    'value' => 'acf/uams-news',
                ),
            ),
        ),
        'menu_order' => 0,
        'position' => 'normal',
        'style' => 'default',
        'label_placement' => 'top',
        'instruction_placement' => 'label',
        'hide_on_screen' => '',
        'active' => true,
        'description' => '',
    ));

    // Add local field group for UAMS Text & Image Overlay Block
    acf_add_local_field_group(array(
        'key' => 'group_5cfa9e13cb394',
        'title' => 'Block: UAMS Text & Image Overlay',
        'fields' => $overlay,
        'location' => array(
            array(
                array(
                    'param' => 'block',
                    'operator' => '==',
                    'value' => 'acf/text-overlay',
                ),
            ),
        ),
        'menu_order' => 0,
        'position' => 'normal',
        'style' => 'default',
        'label_placement' => 'top',
        'instruction_placement' => 'label',
        'hide_on_screen' => '',
        'active' => true,
        'description' => '',
    ));

    // Add local field group for UAMS Post Category Tile Block
    acf_add_local_field_group(array(
        'key' => 'group_5d03e9584d86d',
        'title' => 'Block: Post Category Tile Single',
        'fields' => $post_tile,
        'location' => array(
            array(
                array(
                    'param' => 'block',
                    'operator' => '==',
                    'value' => 'acf/post-category-tile',
                ),
            ),
        ),
        'menu_order' => 0,
        'position' => 'normal',
        'style' => 'default',
        'label_placement' => 'top',
        'instruction_placement' => 'label',
        'hide_on_screen' => '',
        'active' => true,
        'description' => '',
    ));

    // Add local field group for UAMS Post Category Tiles Block
    acf_add_local_field_group(array(
        'key' => 'group_5d03aeab567b9',
        'title' => 'Block: Post Category Tiles',
        'fields' => $post_tiles,
        'location' => array(
            array(
                array(
                    'param' => 'block',
                    'operator' => '==',
                    'value' => 'acf/post-category-tiles',
                ),
            ),
        ),
        'menu_order' => 0,
        'position' => 'normal',
        'style' => 'default',
        'label_placement' => 'top',
        'instruction_placement' => 'label',
        'hide_on_screen' => '',
        'active' => true,
        'description' => '',
    ));

    // Add local field group for UAMS Side-by-Side Image & Text Block
    acf_add_local_field_group(array(
        'key' => 'group_5cefe13df1b97',
        'title' => 'Block: Side-by-Side Image & Text',
        'fields' => $side_by_side,
        'location' => array(
            array(
                array(
                    'param' => 'block',
                    'operator' => '==',
                    'value' => 'acf/image-side',
                ),
            ),
        ),
        'menu_order' => 0,
        'position' => 'normal',
        'style' => 'default',
        'label_placement' => 'left',
        'instruction_placement' => 'label',
        'hide_on_screen' => '',
        'active' => true,
        'description' => '',
    ));

    // Add local field group for UAMS Stacked Image & Text Block
    acf_add_local_field_group(array(
        'key' => 'group_5cfab4f342f6d',
        'title' => 'Block: UAMS Stacked Image & Text',
        'fields' => $stacked,
        'location' => array(
            array(
                array(
                    'param' => 'block',
                    'operator' => '==',
                    'value' => 'acf/text-stacked',
                ),
            ),
        ),
        'menu_order' => 0,
        'position' => 'normal',
        'style' => 'default',
        'label_placement' => 'top',
        'instruction_placement' => 'label',
        'hide_on_screen' => '',
        'active' => true,
        'description' => '',
    ));

    // Add local field group for UAMS LiveWhale Calendar Block
    acf_add_local_field_group(array(
        'key' => 'group_livewhale',
        'title' => 'Block: UAMS LiveWhale Calendar',
        'fields' => $livewhale,
        'location' => array(
            array(
                array(
                    'param' => 'block',
                    'operator' => '==',
                    'value' => 'acf/livewhale-calendar',
                ),
            ),
        ),
        'menu_order' => 0,
        'position' => 'normal',
        'style' => 'default',
        'label_placement' => 'top',
        'instruction_placement' => 'label',
        'hide_on_screen' => '',
        'active' => true,
        'description' => '',
    ));

    // Add local field group for UAMS Gallery Block
    acf_add_local_field_group(array(
        'key' => 'group_uams_gallery',
        'title' => 'Block: UAMS Gallery',
        'fields' => $gallery,
        'location' => array(
            array(
                array(
                    'param' => 'block',
                    'operator' => '==',
                    'value' => 'acf/uams-gallery',
                ),
            ),
        ),
        'menu_order' => 0,
        'position' => 'normal',
        'style' => 'default',
        'label_placement' => 'top',
        'instruction_placement' => 'label',
        'hide_on_screen' => '',
        'active' => true,
        'description' => '',
    ));

    // Add local field group for UAMS Content Block
    acf_add_local_field_group(array(
        'key' => 'group_uams_content',
        'title' => 'Block: UAMS Content',
        'fields' => $content,
        'location' => array(
            array(
                array(
                    'param' => 'block',
                    'operator' => '==',
                    'value' => 'acf/uams-content',
                ),
            ),
        ),
        'menu_order' => 0,
        'position' => 'normal',
        'style' => 'default',
        'label_placement' => 'top',
        'instruction_placement' => 'label',
        'hide_on_screen' => '',
        'active' => true,
        'description' => '',
    ));
    
    endif;