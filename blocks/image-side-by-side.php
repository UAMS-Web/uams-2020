<?php 
/*
 *
 * UAMS Image Side-by-side Block
 * 
 */
// Create id attribute allowing for custom "anchor" value.
if ( empty( $id ) ) {
    $id = $block['id'];
}
$id = 'image-side-' . $id;
if( !empty($block['anchor']) ) {
    $id = $block['anchor'];
}

// Create class attribute allowing for custom "className" and "align" values.
$className = 'uams-side-by-side-block';
if( !empty($block['className']) ) {
    $className .= ' ' . $block['className'];
}
if( !empty($block['align']) ) {
    $className .= ' align' . $block['align'];
}

// Load values and setting defaults.
// If values are empty => used for modules & widgets
if ( empty($layout) ) 
    $layout = get_field('side_text_layout') ?: 'link-list';
if ( empty($heading) ) 
    $heading = get_field('side_heading') ?: 'Heading goes here...';

if ( empty($body) ){
    if ( $layout == 'body-only' ) {
        $body = get_field('side_layout_body_text') ?: 'This is where the body-only description goes';
    } else {
        $body = get_field('side_layout_link_text') ?: 'This is where the body + link list description goes';
    }
}

if ( empty($link_list) ) 
    $link_list = get_field('side_link_list') ?: '';
if ( empty($list_more) ) 
    $list_more = get_field('side_link_include_more') ?: '';
if ( empty($cta) ) 
    $cta = get_field('side_cta') ?: '';
    $cta_text = $cta['side_cta_text'] ?: '';
    $cta_link = $cta['side_cta_url'] ?: '';
    $cta_target = $cta['side_cta_target'] ?: '';
    $cta_desc = $cta['side_cta_description'] ?: '';
if ( empty($image_group) ) 
    $image_group = get_field('side_image')?: '';
    $side_image = $image_group['side_image_image'] ?: '';
    $image_alt = $image_group['side_image_alt_text'] ?: '';
    $image_crop = $image_group['side_image_crop'] ?: '';
if ( empty($image_postion) ) 
    $image_postion = get_field('side_image_position') ?: 'left';
if ( empty($image_anchor) ) 
    $image_anchor = get_field('side_image_anchor') ?: 'center';
if ( empty($background_color) ) 
    $background_color = get_field('side_image_background_color') ?: 'bg-white';

if ( empty($image_alt) ) 
    $image_alt = $image_alt ? $image_alt : get_post_meta($side_image, '_wp_attachment_image_alt', true);
    $cta_target = $cta_target ? ' target="blank"' : '';
    $cta_desc = $cta_desc ? ' aria-label="'.$cta_desc.'"' : '';
    $cta_link = $cta_link ? '<a class="btn btn-primary" href="'. $cta_link .'"' . $cta_desc . $cta_target . '>' : '';
    $side_image_width = wp_get_attachment_image_src($side_image, 'full')[1];
// $side_image_height = wp_get_attachment_image_src($side_image, 'full')[2];

// Break it if it isn't "Landing" template
// $page_template = get_page_template_slug( $post_id );
// // echo $page_template;
// if ('templates/page_landing.php' != $page_template) {
//     echo '<h4>This template is not supported. Please select "Landing"'. $page_template .'</h4>';
//     return;
// }


?>
<section class="uams-module no-padding side-by-side image-on-<?php echo $image_postion; ?> image-background-<?php echo $image_anchor; ?> <?php echo $background_color; ?>" id="side-by-side-<?php echo esc_attr($id); ?>">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12 col-md-6 image-container" aria-label="<?php echo $image_alt ?>" role="img">

                <?php if ( function_exists( 'fly_add_image_size' ) ) { ?>
                <style>
                    #side-by-side-<?php echo esc_attr($id); ?> .image-container {
                        background-image: url("<?php echo image_sizer($side_image, 576, 324, $image_postion, $image_anchor); ?>");
                    }

                    /* XXS Breakpoint, retina */
                    @media (-webkit-min-device-pixel-ratio: 2),
                    (min-resolution: 192dpi) {
                        #side-by-side-<?php echo esc_attr($id); ?> .image-container {
                            background-image: url("<?php echo image_sizer($side_image, 1152, 648, $image_postion, $image_anchor); ?>");
                        }
                    }

                    /* XS Breakpoint */
                    @media (min-width: 576px) {
                        #side-by-side-<?php echo esc_attr($id); ?> .image-container {
                            background-image: url("<?php echo image_sizer($side_image, 768, 432, $image_postion, $image_anchor); ?>");
                        }
                    }

                    /* XS Breakpoint, retina */
                    @media (min-width: 576px) and (-webkit-min-device-pixel-ratio: 2),
                    (min-width: 576px) and (min-resolution: 192dpi) {
                        #side-by-side-<?php echo esc_attr($id); ?> .image-container {
                            background-image: url("<?php echo image_sizer($side_image, 1536, 864, $image_postion, $image_anchor); ?>");
                        }
                    }

                    /* SM Breakpoint */
                    @media (min-width: 768px) {
                        #side-by-side-<?php echo esc_attr($id); ?> .image-container {
                            background-image: url("<?php echo image_sizer($side_image, 992, 558, $image_postion, $image_anchor); ?>");
                        }
                    }

                    /* SM Breakpoint, retina */
                    @media (min-width: 768px) and (-webkit-min-device-pixel-ratio: 2),
                    (min-width: 768px) and (min-resolution: 192dpi) {
                        #side-by-side-<?php echo esc_attr($id); ?> .image-container {
                            background-image: url("<?php echo image_sizer($side_image, 1984, 1116, $image_postion, $image_anchor); ?>");
                        }
                    }

                    /* MD Breakpoint */
                    @media (min-width: 992px) {
                        #side-by-side-<?php echo esc_attr($id); ?> .image-container {
                            background-image: url("<?php echo image_sizer($side_image, 1200, 675, $image_postion, $image_anchor); ?>");
                        }
                    }

                    /* MD Breakpoint, retina */
                    @media (min-width: 992px) and (-webkit-min-device-pixel-ratio: 2),
                    (min-width: 992px) and (min-resolution: 192dpi) {
                        #side-by-side-<?php echo esc_attr($id); ?> .image-container {
                            background-image: url("<?php echo image_sizer($side_image, 2400, 1350, $image_postion, $image_anchor); ?>");
                        }
                    }

                    /* LG Breakpoint */
                    @media (min-width: 1200px) {
                        #side-by-side-<?php echo esc_attr($id); ?> .image-container {
                            background-image: url("<?php echo image_sizer($side_image, 1500, 844, $image_postion, $image_anchor); ?>");
                        }
                    }

                    /* LG Breakpoint, retina */
                    @media (min-width: 1200px) and (-webkit-min-device-pixel-ratio: 2),
                    (min-width: 1200px) and (min-resolution: 192dpi) {
                        #side-by-side-<?php echo esc_attr($id); ?> .image-container {
                            background-image: url("<?php echo image_sizer($side_image, 3000, 1688, $image_postion, $image_anchor); ?>");
                        }
                    }

                    /* XL Breakpoint */
                    @media (min-width: 1500px) {
                        #side-by-side-<?php echo esc_attr($id); ?> .image-container {
                            background-image: url("<?php echo image_sizer($side_image, 1921, 1081, $image_postion, $image_anchor); ?>");
                        }
                    }

                    /* XL Breakpoint, retina */
                    @media (min-width: 1500px) and (-webkit-min-device-pixel-ratio: 2),
                    (min-width: 1500px) and (min-resolution: 192dpi) {
                        #side-by-side-<?php echo esc_attr($id); ?> .image-container {
                            background-image: url("<?php echo image_sizer($side_image, 3842, 2161, $image_postion, $image_anchor); ?>");
                        }
                    }

                    /* XXL Breakpoint */
                    @media (min-width: 1921px) {
                        #side-by-side-<?php echo esc_attr($id); ?> .image-container {
                            background-image: url("<?php echo image_sizer($side_image, 2560, 1440, $image_postion, $image_anchor); ?>");
                        }
                    }

                    /* XXL Breakpoint, retina */
                    @media (min-width: 1921px) and (-webkit-min-device-pixel-ratio: 2),
                    (min-width: 1921px) and (min-resolution: 192dpi) {
                        #side-by-side-<?php echo esc_attr($id); ?> .image-container {
                            background-image: url("<?php echo image_sizer($side_image, 5120, 2880, $image_postion, $image_anchor ); ?>");
                        }
                    }
                </style>
                <?php } else { ?>
                <style>
                    #side-by-side-<?php echo esc_attr($id); ?> .image-container {
                        background-image: url("<?php echo wp_get_attachment_url( $side_image, 'aspect-16-9' ); ?>");
                    }
                </style>
                <?php } //endif ?>
                <div class="image-inner-container">
                </div>
            </div>
            <div class="col-12 col-md-6 text-container">
                <div class="text-inner-container">
                    <h2 class="h3"><?php echo $heading; ?></h2>
                    <?php echo !empty($body) ? '<p>' . $body . '</p>' : ''; ?>
                    <?php if ($link_list): ?>
                    <ul>
                        <?php foreach( $link_list as $link ) {
                            $list_text = $link['side_link_list_text'];
                            $list_url = $link['side_link_list_url'];
                            $list_desc = $link['side_link_list_description'];
                        ?>
                        <li>
                            <?php if( $list_url ): ?>
                            <a href="<?php echo $list_url; ?>"<?php echo $list_desc ? ' aria-label="' . $list_desc . '"' : ''; ?>>
                            <?php endif; ?>
                            <?php if( $list_text ): ?>
                                <?php echo $list_text; ?>
                            <?php endif; ?>
                            <?php if( $list_url ): ?>
                            </a>
                            <?php endif; ?>
                        </li>
                        <?php } //end foreach ?>
                        <?php echo $list_more ? '<li><em>and more</em></li>' : ''; ?>
                    </ul>
                    <?php endif;
                        if( $cta_link ){
                            echo $cta_link;
                            echo $cta_text ? $cta_text : 'Learn More'; 
                            echo '</a>';
                        } ?>
                </div>
            </div>
        </div>
    </div>
</section>
