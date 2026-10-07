<?php

/*
 *
 * Custom ACF Blocks
 * 
 */

/**
 * Child blocks allowed inside the uams-section InnerBlocks.
 * Shared by blocks/section.php and the Marketing Landing Page allowlist in functions.php.
 */
function uamswp_section_allowed_blocks() {
    $blocks = array( 'core/heading', 'core/paragraph', 'core/embed', 'core/list', 'core/list-item', 'core/quote', 'core/image', 'core/shortcode', 'core/table', 'core/file', 'gravityforms/form', 'formidable/simple-form' );
    if ( current_user_can( 'manage_options' ) ) {
        $blocks[] = 'acf/uams-iframe';
    }
    return apply_filters( 'uamswp_section_allowed_blocks', $blocks );
}

/**
 * Editor placeholder for ACF blocks that have no content yet.
 *
 * ACF Blocks V3 always render the template as a preview, so a freshly inserted
 * block would otherwise show an empty section. Returns true (after printing the
 * placeholder) when the caller should stop rendering.
 *
 * Safe outside block context (flexible content modules, widgets): $block and
 * $is_preview are not set there, so this returns false.
 */
function uamswp_block_placeholder( $block = null, $is_preview = false ) {
    if ( empty( $is_preview ) || empty( $block ) || ! is_array( $block ) ) {
        return false;
    }
    $data = isset( $block['data'] ) && is_array( $block['data'] ) ? $block['data'] : array();
    foreach ( $data as $key => $value ) {
        // ACF stores "_field_name" => field key reference entries alongside values.
        if ( is_string( $key ) && 0 === strpos( $key, '_' ) ) {
            continue;
        }
        if ( '' !== $value && null !== $value && array() !== $value && false !== $value && '0' !== $value ) {
            return false;
        }
    }
    $title = ! empty( $block['title'] ) ? $block['title'] : __( 'UAMS block' );
    printf(
        '<div class="uams-block-placeholder" style="padding:1.5rem;border:1px dashed currentColor;text-align:center;"><strong>%s</strong><br>%s</div>',
        esc_html( $title ),
        esc_html__( 'Click the pencil icon in the block toolbar to add content.' )
    );
    return true;
}

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
            'acf_block_version' => 3,
            'align'             => 'full',
            'supports'          => array( 'anchor' => true ),
            'render_template'   => 'blocks/action-bar.php',
        ));
        acf_register_block_type(array(
            'name'              => 'call-out',
            'title'             => __('UAMS Call-Out'),
            'description'       => __('Call-Out.'),
            'category'          => 'common',
            'icon'              => 'megaphone',
            'keywords'          => array('uams', 'callout', 'call-out', 'text'),
            'acf_block_version' => 3,
            'align'             => 'full',
            'supports'          => array( 'anchor' => true ),
            'render_template'   => 'blocks/call-out.php',
        ));
        acf_register_block_type(array(
            'name'              => 'cta',
            'title'             => __('UAMS CTA Bar'),
            'description'       => __('Call-to-Action (CTA) Bar.'),
            'category'          => 'common',
            'icon'              => 'format-status',
            'keywords'          => array('uams', 'cta', 'call-to-action', 'call to action', 'button'),
            'acf_block_version' => 3,
            'align'             => 'full',
            'supports'          => array( 'anchor' => true ),
            'render_template'   => 'blocks/cta.php',
        ));
        acf_register_block_type(array(
            'name'              => 'hero',
            'title'             => __('UAMS Hero'),
            'description'       => __('UAMS Hero / Slideshow.'),
            'category'          => 'common',
            'icon'              => 'images-alt2',
            'keywords'          => array('uams', 'slides', 'slideshow', 'hero'),
            'acf_block_version' => 3,
            'align'             => 'full',
            'supports'          => array( 'anchor' => true ),
            'render_template'   => 'blocks/hero.php',
        ));
        acf_register_block_type(array(
            'name'              => 'link-list',
            'title'             => __('UAMS Link List'),
            'description'       => __('A list of linked tiles.'),
            'category'          => 'common',
            'icon'              => 'admin-links',
            'keywords'          => array('uams', 'link', 'links', 'list'),
            'acf_block_version' => 3,
            'align'             => 'full',
            'supports'          => array( 'anchor' => true ),
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
                'acf_block_version' => 3,
                'align'             => 'full',
                'supports'          => array( 'anchor' => true ),
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
            'acf_block_version' => 3,
            'align'             => 'full',
            'supports'          => array( 'anchor' => true ),
            'render_template'   => 'blocks/overlay.php',
        ));
        // acf_register_block_type(array(
        //     'name'              => 'post-category-tile',
        //     'title'             => __('UAMS Post Category Tile (Single)'),
        //     'description'       => __('One tile displaying a post from an individual post category. This block is only intended to be used on the sidebar layout.'),
        //     'category'          => 'common',
        //     'icon'              => 'screenoptions',
        //     'keywords'          => array('uams', 'news', 'posts', 'post', 'articles', 'article', 'link', 'links', 'intranet', 'inside', 'tile', 'tiles', 'sidebar', 'side bar'),
        //     'acf_block_version' => 3,
        //     'align'             => 'full',
        //     'render_template'   => 'blocks/post-category-tile.php',
        // ));
        // acf_register_block_type(array(
        //     'name'              => 'post-category-tiles',
        //     'title'             => __('UAMS Post Category Tile (Double)'),
        //     'description'       => __('Two tiles displaying posts from individual post categories. This block is only intended to be used on the sidebar layout.'),
        //     'category'          => 'common',
        //     'icon'              => 'screenoptions',
        //     'keywords'          => array('uams', 'news', 'posts', 'post', 'articles', 'article', 'link', 'links', 'intranet', 'inside', 'tile', 'tiles', 'sidebar', 'side bar'),
        //     'acf_block_version' => 3,
        //     'align'             => 'full',
        //     'render_template'   => 'blocks/post-category-tiles.php',
        // ));
        acf_register_block_type(array(
            'name'              => 'image-side',
            'title'             => __('UAMS Side-by-Side Image & Text'),
            'description'       => __('Image on one side, text on the other side.'),
            'category'          => 'common',
            'icon'              => 'id',
            'keywords'          => array('uams', 'text', 'image', 'side'),
            'acf_block_version' => 3,
            'align'             => 'full',
            'supports'          => array( 'anchor' => true ),
            'render_template'   => 'blocks/image-side-by-side.php',
        ));
        acf_register_block_type(array(
            'name'              => 'text-stacked',
            'title'             => __('UAMS Stacked Image & Text'),
            'description'       => __('Stacked Image & Text'),
            'category'          => 'common',
            'icon'              => 'screenoptions',
            'keywords'          => array('uams', 'text', 'image', 'stack', 'stacked'),
            'acf_block_version' => 3,
            'align'             => 'full',
            'supports'          => array( 'anchor' => true ),
            'render_template'   => 'blocks/stacked.php',
        ));
        acf_register_block_type(array(
            'name'              => 'livewhale-calendar',
            'title'             => __('UAMS LiveWhale Calendar'),
            'description'       => __('LiveWhale widget'),
            'category'          => 'common',
            'icon'              => 'calendar-alt',
            'keywords'          => array('uams', 'calendar', 'livewhale'),
            'acf_block_version' => 3,
            'align'             => 'full',
            'supports'          => array( 'anchor' => true ),
            'render_template'   => 'blocks/livewhale.php',
        ));
        acf_register_block_type(array(
            'name'              => 'uams-gallery',
            'title'             => __('UAMS Gallery'),
            'description'       => __('Custom Gallery with lightbox'),
            'category'          => 'common',
            'icon'              => 'format-gallery',
            'keywords'          => array('uams', 'gallery'),
            'acf_block_version' => 3,
            'align'             => 'full',
            'supports'          => array( 'anchor' => true ),
            'render_template'   => 'blocks/gallery.php',
        ));
        acf_register_block_type(array(
            'name'              => 'uams-content',
            'title'             => __('UAMS Content Block'),
            'description'       => __('Base content is a section block'),
            'category'          => 'common',
            'icon'              => 'analytics',
            'keywords'          => array('uams', 'content'),
            'acf_block_version' => 3,
            'align'             => 'full',
            'supports'          => array( 'anchor' => true, 'inserter' => false ), // Deprecated: use uams-section. Existing instances still render and edit.
            'render_template'   => 'blocks/content.php',
		));
        acf_register_block_type(array(
            'name'              => 'counter-list',
            'title'             => __('UAMS Counter List'),
            'description'       => __('Counter List'),
            'category'          => 'common',
            'icon'              => 'clock',
            'keywords'          => array('uams', 'counter', 'list'),
            'acf_block_version' => 3,
            'align'             => 'full',
            'supports'          => array( 'anchor' => true ),
            'render_template'   => 'blocks/counter.php',
        ));
        // acf_register_block_type(array(
        //     'name'              => 'block',
        //     'title'             => __('UAMS Block'),
        //     'description'       => __('A custom hero block.'),
        //     'category'          => 'common',
        //     'icon'              => '',
        //     'keywords'          => array('uams', 'block'),
        //     'acf_block_version' => 3,
        //     'align'             => 'full',
        //     'render_template'   => 'blocks/block.php',
		// ));
        acf_register_block_type(array(
            'name'              => 'uams-section',
            'title'             => __('UAMS Section'),
            'description'       => __('Section - Inner block.'),
            'category'          => 'common',
            'icon'              => '',
            'keywords'          => array('uams', 'inner', 'block'),
            'acf_block_version' => 3,
            'supports'          => [
                'align'             => true,
                'anchor'            => true,
                'customClassName'   => true,
                'jsx'               => true,
            ],
            'render_template'   => 'blocks/section.php',
		));
        acf_register_block_type(array(
            'name'              => 'uams-iframe',
            'title'             => __('UAMS iFrame'),
            'description'       => __('Embed an external page in an iframe. Administrators only.'),
            'category'          => 'embed',
            'icon'              => 'editor-code',
            'keywords'          => array('uams', 'iframe', 'embed'),
            'acf_block_version' => 3,
            'supports'          => array(
                'anchor' => true,
                'align'  => array( 'wide', 'full' ),
            ),
            'render_template'   => 'blocks/iframe.php',
        ));
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
    // $post_tile = require( get_stylesheet_directory() .'/acf_fields/post-category-tile.php' );
    // $post_tiles = require( get_stylesheet_directory() .'/acf_fields/post-category-tiles.php' );
    $side_by_side = require( get_stylesheet_directory() .'/acf_fields/image-side-by-side.php' );
    $stacked = require( get_stylesheet_directory() .'/acf_fields/stacked.php' );
    $livewhale = require( get_stylesheet_directory() .'/acf_fields/livewhale.php' );
    $gallery = require( get_stylesheet_directory() .'/acf_fields/gallery.php' );
    $content = require( get_stylesheet_directory() .'/acf_fields/content.php' );
    $section = require( get_stylesheet_directory() .'/acf_fields/section.php' );
    $counter_list = require( get_stylesheet_directory() .'/acf_fields/counter.php' );
    

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
    // acf_add_local_field_group(array(
    //     'key' => 'group_5d03e9584d86d',
    //     'title' => 'Block: Post Category Tile Single',
    //     'fields' => $post_tile,
    //     'location' => array(
    //         array(
    //             array(
    //                 'param' => 'block',
    //                 'operator' => '==',
    //                 'value' => 'acf/post-category-tile',
    //             ),
    //         ),
    //     ),
    //     'menu_order' => 0,
    //     'position' => 'normal',
    //     'style' => 'default',
    //     'label_placement' => 'top',
    //     'instruction_placement' => 'label',
    //     'hide_on_screen' => '',
    //     'active' => true,
    //     'description' => '',
    // ));

    // Add local field group for UAMS Post Category Tiles Block
    // acf_add_local_field_group(array(
    //     'key' => 'group_5d03aeab567b9',
    //     'title' => 'Block: Post Category Tiles',
    //     'fields' => $post_tiles,
    //     'location' => array(
    //         array(
    //             array(
    //                 'param' => 'block',
    //                 'operator' => '==',
    //                 'value' => 'acf/post-category-tiles',
    //             ),
    //         ),
    //     ),
    //     'menu_order' => 0,
    //     'position' => 'normal',
    //     'style' => 'default',
    //     'label_placement' => 'top',
    //     'instruction_placement' => 'label',
    //     'hide_on_screen' => '',
    //     'active' => true,
    //     'description' => '',
    // ));

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

    // Add local field group for UAMS Counter List Block
    acf_add_local_field_group(array(
        'key' => 'group_uams_counter_list',
        'title' => 'Block: UAMS Counter List',
        'fields' => $counter_list,
        'location' => array(
            array(
                array(
                    'param' => 'block',
                    'operator' => '==',
                    'value' => 'acf/counter-list',
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

    // Add local field group for UAMS Section Block
    acf_add_local_field_group(array(
        'key' => 'group_uams_section',
        'title' => 'Block: UAMS Section',
        'fields' => $section,
        'location' => array(
            array(
                array(
                    'param' => 'block',
                    'operator' => '==',
                    'value' => 'acf/uams-section',
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

/**
 * UAMS iFrame block fields.
 */
add_action( 'acf/init', 'uamswp_iframe_block_fields' );
function uamswp_iframe_block_fields() {
    if ( ! function_exists( 'acf_add_local_field_group' ) ) {
        return;
    }
    acf_add_local_field_group( array(
        'key'    => 'group_block_uams_iframe',
        'title'  => 'Block: UAMS iFrame',
        'fields' => array(
            array(
                'key'          => 'field_iframe_url_b',
                'label'        => 'URL',
                'name'         => 'iframe_url',
                'type'         => 'url',
                'instructions' => 'Must be an https:// address.',
                'required'     => 1,
            ),
            array(
                'key'          => 'field_iframe_title_b',
                'label'        => 'Title',
                'name'         => 'iframe_title',
                'type'         => 'text',
                'instructions' => 'Describes the embedded content for screen reader users (e.g. "Clinic location map").',
                'required'     => 1,
            ),
            array(
                'key'           => 'field_iframe_height_b',
                'label'         => 'Height',
                'name'          => 'iframe_height',
                'type'          => 'number',
                'append'        => 'px',
                'default_value' => 600,
                'min'           => 100,
                'max'           => 3000,
            ),
            array(
                'key'           => 'field_iframe_fullscreen_b',
                'label'         => 'Allow fullscreen',
                'name'          => 'iframe_allow_fullscreen',
                'type'          => 'true_false',
                'ui'            => 1,
                'default_value' => 0,
            ),
        ),
        'location' => array(
            array(
                array(
                    'param'    => 'block',
                    'operator' => '==',
                    'value'    => 'acf/uams-iframe',
                ),
            ),
        ),
        'active' => true,
    ) );
}

/**
 * Hosts the UAMS iFrame block may embed. Empty array = any https host.
 * Filter to lock this down, e.g. return array( 'calendar.uams.edu', 'www.google.com' );
 */
function uamswp_iframe_allowed_hosts() {
    return (array) apply_filters( 'uamswp_iframe_allowed_hosts', array() );
}

/**
 * Validate an iframe URL: https only, and on the host allowlist when one is set.
 */
function uamswp_iframe_url_is_allowed( $url ) {
    $parts = wp_parse_url( $url );
    if ( empty( $parts['scheme'] ) || 'https' !== strtolower( $parts['scheme'] ) || empty( $parts['host'] ) ) {
        return false;
    }
    $hosts = uamswp_iframe_allowed_hosts();
    return empty( $hosts ) || in_array( strtolower( $parts['host'] ), array_map( 'strtolower', $hosts ), true );
}

add_filter( 'acf/validate_value/key=field_iframe_url_b', function( $valid, $value ) {
    if ( true !== $valid || '' === $value ) {
        return $valid;
    }
    return uamswp_iframe_url_is_allowed( $value ) ? true : __( 'Use an https:// address on an approved host.' );
}, 10, 2 );

// Only administrators can see/edit the iframe fields. Non-admins editing a page
// that already contains the block see the preview but no fields.
foreach ( array( 'field_iframe_url_b', 'field_iframe_title_b', 'field_iframe_height_b', 'field_iframe_fullscreen_b' ) as $uamswp_iframe_field_key ) {
    add_filter( 'acf/prepare_field/key=' . $uamswp_iframe_field_key, function( $field ) {
        return current_user_can( 'manage_options' ) ? $field : false;
    } );
}
unset( $uamswp_iframe_field_key );

