<?php 
/*
 *
 * UAMS Hero Block
 * 
 */
// Create id attribute allowing for custom "anchor" value.
$id = 'hero-' . $block['id'];
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
$layout = get_field('side_text-layout') ?: 'link-list';
$heading = get_field('side_heading') ?: 'Heading goes here...';

if ( $layout == 'body-only' ) {
    $body = get_field('side_layout-body-only_body') ?: 'This is where the body-only description goes';
} else {
    $body = get_field('side_layout-link-list_body') ?: 'This is where the body + link list description goes';
}

$link_list = get_field('side_link-list') ?: '';
$list_more = get_field('side_link-list_include-more') ?: '';
$cta = get_field('side_cta') ?: '';
$cta_text = $cta['side_cta_text'] ?: '';
$cta_link = $cta['side_cta_url'] ?: '';
$cta_target = $cta['side_cta_target'] ?: '';
$cta_desc = $cta['side_cta_description'] ?: '';
$image_group = get_field('side_image')?: '';
$side_image = $image_group['side_image_image'] ?: '';
$image_alt = $image_group['side_image_alt-text'] ?: '';
$image_postion = get_field('side_image_position') ?: 'left';
$image_anchor = get_field('side_image_anchor') ?: 'center';
$background_color = get_field('side_image_background-color') ?: 'bg-white';

$image_alt = $image_alt ? $image_alt : get_post_meta($side_image, '_wp_attachment_image_alt', true);
$cta_target = $cta_target ? ' target="blank"' : '';
$cta_desc = $cta_desc ? ' aria-label="'.$cta_desc.'"' : '';
$cta_link = $cta_link ? '<a class="btn btn-primary" href="'. $cta_link .'"' . $cta_desc . $cta_target . '>' : '';
$side_image_width = wp_get_attachment_image_src($side_image, 'full')[1];
// $side_image_height = wp_get_attachment_image_src($side_image, 'full')[2];

// Break it if it isn't "Landing" template
$page_template = get_page_template_slug( $post_id );
// echo $page_template;
if ('templates/page_landing.php' != $page_template) {
    echo '<h4>This template is not supported. Please select "Landing"'. $page_template .'</h4>';
    return;
}


?>
<section class="uams-module no-padding side-by-side image-on-<?php echo $image_postion; ?> image-background-<?php echo $image_anchor; ?> <?php echo $background_color; ?>" id="side-by-side-<?php echo esc_attr($id); ?>">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12 col-md-6 image-container">
                <div class="image-inner-container">
                    <?php if ( function_exists( 'fly_add_image_size' ) ) : ?>
                    <style>
                        <?php //echo $side_image_width . 'x' . $side_image_height; ?>
                        
                        /* MD Breakpoint */
                        @media (min-width: 992px) {
                            #side-by-side-<?php echo esc_attr($id); ?> .image-inner-container {
                                background-image: url("<?php echo image_sizer($side_image, 1200, 675, 'center', 'center'); ?>");
                            }
                        }

                        /* MD Breakpoint, retina */
                        @media (min-width: 992px) and (-webkit-min-device-pixel-ratio: 2),
                        (min-width: 992px) and (min-resolution: 192dpi) {
                            #side-by-side-<?php echo esc_attr($id); ?> .image-inner-container {
                                background-image: url("<?php echo image_sizer($side_image, 2400, 1350, 'center', 'center'); ?>");
                            }
                        }

                        /* LG Breakpoint */
                        @media (min-width: 1200px) {
                            #side-by-side-<?php echo esc_attr($id); ?> .image-inner-container {
                                background-image: url("<?php echo image_sizer($side_image, 1500, 844, 'center', 'center'); ?>");
                            }
                        }

                        /* LG Breakpoint, retina */
                        @media (min-width: 1200px) and (-webkit-min-device-pixel-ratio: 2),
                        (min-width: 1200px) and (min-resolution: 192dpi) {
                            #side-by-side-<?php echo esc_attr($id); ?> .image-inner-container {
                                background-image: url("<?php echo image_sizer($side_image, 3000, 1688, 'center', 'center'); ?>");
                            }
                        }

                        /* XL Breakpoint */
                        @media (min-width: 1500px) {
                            #side-by-side-<?php echo esc_attr($id); ?> .image-inner-container {
                                background-image: url("<?php echo image_sizer($side_image, 1921, 1081, 'center', 'center') ?>");
                            }
                        }

                        /* XL Breakpoint, retina */
                        @media (min-width: 1500px) and (-webkit-min-device-pixel-ratio: 2),
                        (min-width: 1500px) and (min-resolution: 192dpi) {
                            #side-by-side-<?php echo esc_attr($id); ?> .image-inner-container {
                                background-image: url("<?php //echo image_sizer($side_image, 3842, 2161, 'center', 'center'); ?>");
                            }
                        }

                        /* XXL Breakpoint */
                        @media (min-width: 1500px) {
                            #side-by-side-<?php echo esc_attr($id); ?> .image-inner-container {
                                background-image: url("<?php echo image_sizer($side_image, 2560, 1440, 'center', 'center'); ?>");
                            }
                        }

                        /* XXL Breakpoint, retina */
                        @media (min-width: 1500px) and (-webkit-min-device-pixel-ratio: 2),
                        (min-width: 1500px) and (min-resolution: 192dpi) {
                            #side-by-side-<?php echo esc_attr($id); ?> .image-inner-container {
                                background-image: url("<?php echo image_sizer($side_image, 5120, 2880, 'center', 'center'); ?>");
                            }
                        }
                    </style>
                    <?php endif; ?>
                    <picture>
                        <?php if ( function_exists( 'fly_add_image_size' ) ) { ?>
                        <!-- Mobile and Tablet -->
                        <source srcset="<?php echo image_sizer($side_image, 1984, 1116); ?>"
                            media="(min-width: 768px) and (-webkit-min-device-pixel-ratio: 2), (min-width: 768px) and (min-resolution: 192dpi)">
                        <source srcset="<?php echo image_sizer($side_image, 992, 558, 'center', 'center'); ?>" media="(min-width: 768px)">
                        <source srcset="<?php echo image_sizer($side_image, 1536, 864, 'center', 'center'); ?>"
                            media="(min-width: 576px) and (-webkit-min-device-pixel-ratio: 2), (min-width: 576px) and (min-resolution: 192dpi)">
                        <source srcset="<?php echo image_sizer($side_image, 768, 432, 'center', 'center'); ?>" media="(min-width: 576px)">
                        <source srcset="<?php echo image_sizer($side_image, 1152, 648, 'center', 'center'); ?>"
                            media="(min-width: 1px) and (-webkit-min-device-pixel-ratio: 2), (min-width: 1px) and (min-resolution: 192dpi)">
                        <source srcset="<?php echo image_sizer($side_image, 576, 324, 'center', 'center'); ?>" media="(min-width: 1px)">
                        <?php } //endif ?>
                        <!-- Fallback -->
                        <img src="<?php echo wp_get_attachment_url( $side_image, 'full' ); ?>" alt="<?php echo $image_alt ?>" />
                    </picture>
                </div>
            </div>
            <div class="col-12 col-md-6 text-container">
                <div class="text-inner-container">
                    <h2 class="h3"><?php echo $heading; ?></h2>
                    <?php echo !empty($body) ? '<p>' . $body . '</p>' : ''; ?>
                    <?php if ($link_list): ?>
                    <ul>
                        <?php foreach( $link_list as $link ) {
                            $list_text = $link['side_link-list_text'];
                            $list_url = $link['side_link-list_url'];
                            $list_desc = $link['side_link-list_description'];
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
                            echo !empty($cta_text) ? $cta_text : 'Learn More'; 
                            echo '</a>';
                        } ?>
                </div>
            </div>
        </div>
    </div>
</section>
