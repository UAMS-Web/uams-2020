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
$heading = get_field('side_image_heading') ?: 'Heading goes here...';
$body = get_field('side_image_body') ?: 'This is where the description goes';
$link_list = get_field('side_image_link_list') ?: '';
$list_more = get_field('link_list_include_more') ?: '';
$cta = get_field('side_image_call_to_action') ?: '';
$cta_text = $cta['side_image_cta_text'] ?: '';
$cta_link = $cta['side_image_cta_url'] ?: '';
$cta_target = $cta['side_image_cta_target'] ?: '';
$cta_desc = $cta['side_image_cta_description'] ?: '';
$image = get_field('side_image_image_group')?: '';
$side_image = $image['side_image_image'] ?: '';
$image_alt = $image['image_alt_text'] ?: '';
$image_postion = get_field('side_image_position') ?: 'left';
$image_anchor = get_field('side_image_anchor') ?: 'middle';

$image_alt = $image_alt ? $image_alt : get_post_meta($side_image, '_wp_attachment_image_alt', true);
$cta_target = $cta_target ? ' target="blank"' : '';
$cta_desc = $cta_desc ? ' aria-label="'.$cta_desc.'"' : '';
$cta_link = $cta_link ? '<a class="btn btn-primary" href="'. $cta_link .'"' . $cta_desc . $cta_target . '>' : '';

// Break it if it isn't "Landing" template
$page_template = get_page_template_slug( $post_id );
// echo $page_template;
if ('templates/page_landing.php' != $page_template) {
    echo '<h4>This template is not supported. Please select "Landing"'. $page_template .'</h4>';
    return;
}


?>
<section class="uams-module no-padding side-by-side image-on-<?php echo $image_postion; ?> image-background-<?php echo $image_anchor; ?>" id="side-by-side-<?php echo esc_attr($id); ?>">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12 col-md-6 image-container">
                <div class="image-inner-container">
                    <?php if ( function_exists( 'fly_add_image_size' ) ) : ?>
                    <style>
                        /* MD Breakpoint */
                        @media (min-width: 992px) {
                            #side-by-side-<?php echo esc_attr($id); ?> .image-inner-container {
                                background-image: url("<?php echo fly_get_attachment_image_src( $side_image, array( 1200, 675 ), true )['src']; ?>");
                            }
                        }

                        /* MD Breakpoint, retina */
                        @media (min-width: 992px) and (-webkit-min-device-pixel-ratio: 2),
                        (min-width: 992px) and (min-resolution: 192dpi) {
                            #side-by-side-<?php echo esc_attr($id); ?> .image-inner-container {
                                background-image: url("<?php echo fly_get_attachment_image_src( $side_image, array( 2400, 1350 ), true )['src']; ?>");
                            }
                        }

                        /* LG Breakpoint */
                        @media (min-width: 1200px) {
                            #side-by-side-<?php echo esc_attr($id); ?> .image-inner-container {
                                background-image: url("<?php echo fly_get_attachment_image_src( $side_image, array( 1500, 844 ), true )['src']; ?>");
                            }
                        }

                        /* LG Breakpoint, retina */
                        @media (min-width: 1200px) and (-webkit-min-device-pixel-ratio: 2),
                        (min-width: 1200px) and (min-resolution: 192dpi) {
                            #side-by-side-<?php echo esc_attr($id); ?> .image-inner-container {
                                background-image: url("<?php echo fly_get_attachment_image_src( $side_image, array( 3000, 1688 ), true )['src']; ?>");
                            }
                        }

                        /* XL Breakpoint */
                        @media (min-width: 1500px) {
                            #side-by-side-<?php echo esc_attr($id); ?> .image-inner-container {
                                background-image: url("<?php echo fly_get_attachment_image_src( $side_image, array( 1921, 1081 ), true )['src']; ?>");
                            }
                        }

                        /* XL Breakpoint, retina */
                        @media (min-width: 1500px) and (-webkit-min-device-pixel-ratio: 2),
                        (min-width: 1500px) and (min-resolution: 192dpi) {
                            #side-by-side-<?php echo esc_attr($id); ?> .image-inner-container {
                                background-image: url("<?php echo fly_get_attachment_image_src( $side_image, array( 3842, 2161 ), true )['src']; ?>");
                            }
                        }

                        /* XXL Breakpoint */
                        @media (min-width: 1500px) {
                            #side-by-side-<?php echo esc_attr($id); ?> .image-inner-container {
                                background-image: url("<?php echo fly_get_attachment_image_src( $side_image, array( 2560, 1440 ), true )['src']; ?>");
                            }
                        }

                        /* XXL Breakpoint, retina */
                        @media (min-width: 1500px) and (-webkit-min-device-pixel-ratio: 2),
                        (min-width: 1500px) and (min-resolution: 192dpi) {
                            #side-by-side-<?php echo esc_attr($id); ?> .image-inner-container {
                                background-image: url("<?php echo fly_get_attachment_image_src( $side_image, array( 5120, 2880 ), true )['src']; ?>");
                            }
                        }
                    </style>
                    <?php endif; ?>
                    <picture>
                        <?php if ( function_exists( 'fly_add_image_size' ) ) { ?>
                        <!-- Mobile and Tablet -->
                        <source srcset="<?php echo fly_get_attachment_image_src( $side_image, array( 1984, 1116 ), true )['src']; ?>"
                            media="(min-width: 768px) and (-webkit-min-device-pixel-ratio: 2), (min-width: 768px) and (min-resolution: 192dpi)">
                        <source srcset="<?php echo fly_get_attachment_image_src( $side_image, array( 992, 558 ), true )['src']; ?>" media="(min-width: 768px)">
                        <source srcset="<?php echo fly_get_attachment_image_src( $side_image, array( 1536, 864 ), true )['src']; ?>"
                            media="(min-width: 576px) and (-webkit-min-device-pixel-ratio: 2), (min-width: 576px) and (min-resolution: 192dpi)">
                        <source srcset="<?php echo fly_get_attachment_image_src( $side_image, array( 768, 432 ), true )['src']; ?>" media="(min-width: 576px)">
                        <source srcset="<?php echo fly_get_attachment_image_src( $side_image, array( 1152, 648 ), true )['src']; ?>"
                            media="(min-width: 1px) and (-webkit-min-device-pixel-ratio: 2), (min-width: 1px) and (min-resolution: 192dpi)">
                        <source srcset="<?php echo fly_get_attachment_image_src( $side_image, array( 576, 324 ), true )['src']; ?>" media="(min-width: 1px)">
                        <?php } //endif ?>
                        <!-- Fallback -->
                        <img src="<?php echo wp_get_attachment_url( $side_image ); ?>" alt="<?php echo $image_alt ?>" />
                    </picture>
                </div>
            </div>
            <div class="col-12 col-md-6 text-container">
                <div class="text-inner-container">
                    <h2 class="h3"><?php echo $heading; ?></h2>
                    <?php echo !empty($body) ? $body : ''; ?>
                    <?php if ($link_list): ?>
                    <ul>
                        <?php foreach( $link_list as $link ) {
                            $list_text = $link['side_image_link_list_text'];
                            $list_url = $link['side_image_link_list_url'];
                            // $list_desc = get_sub_field('side_image_link_list_description');
                        ?>
                        <li>
                            <?php if( $list_url ): ?>
                            <a href="<?php echo $list_url; ?>">
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
