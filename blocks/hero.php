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
$className = 'uams-hero-block';
if( !empty($block['className']) ) {
    $className .= ' ' . $block['className'];
}
if( !empty($block['align']) ) {
    $className .= ' align' . $block['align'];
}

if( have_rows('hero') ): 
    $rows = get_field('hero');
    $row_count = count($rows);
?>
    <section class="hero carousel slide<?php echo $row_count > 1 ? " multiple-slides" : ""; ?>" id="carousel-<?php echo esc_attr($id); ?>">
<?php
        $page_template = get_page_template_slug( $post_id );
        // echo $page_template;
        if ('templates/page_landing.php' != $page_template) {
            echo '<h4>This template is not supported. Please select "Landing"</h4>';
            return;
        }
        
        if($row_count > 1) { ?>
            <ol class="carousel-indicators">
                <?php for ($i = 0; $i < $row_count; $i++) { ?>
                <li data-target="#carousel-<?php echo esc_attr($id) ?>" data-slide-to="<?php echo $i; ?>" <?php echo (0 == $i ? 'class="active"' : ''); ?>></li>
                <?php } ?>
            </ol>
<?php   }  ?>
    <div class="carousel-inner" class="<?php echo esc_attr($className); ?>">
<?php 
    while ( have_rows('hero') ) : the_row(); 
// Load values and assing defaults.
    $heading = get_sub_field('hero_heading') ?: 'Heading goes here...';
    $body = get_sub_field('hero_body') ?: 'This is where the description goes';
    $button_text = get_sub_field('hero_button_text') ?: 'Learn More';
    $button_url = get_sub_field('hero_button_url') ?: '';
    $button_target = get_sub_field('hero_button_target') ?: '';
    $button_desc = get_sub_field('hero_button_description') ?: '';
    $image_desktop = get_sub_field('image_desktop') ?: ''; // Required
    $image_tablet = get_sub_field('image_tablet') ?: '';
    $image_mobile = get_sub_field('image_mobile') ?: '';
    $image_alt = get_sub_field('image_alt_text') ?: '';
    $background_color = get_sub_field('background_color')?: 'auto';

    // If the image alt text override field is empty, assign the normal alt value to the variable
    $image_alt = $image_alt ? $image_alt : get_post_meta($image_desktop, '_wp_attachment_image_alt', true);
    
    // If image for tablet or mobile is empty, assign desktop's id to those variables
    if ( empty($image_tablet) ) {
        $image_tablet = $image_desktop;
    }
    if ( empty($image_mobile) ) {
        $image_mobile = $image_desktop;
    }
?>
        <div class="carousel-item <?php echo $background_color; ?><?php echo (0 == (get_row_index() - 1) ? ' active' : ''); ?>" id="carousel-item-<?php echo (get_row_index() - 1); ?>">
            <div class="image-container">
                <style>
                    /* Tablet Image, Aspect ratio 1.4132:1 */
                    /* SM Breakpoint */
                    @media (min-width: 768px) {
                        #carousel-<?php echo esc_attr($id); ?> #carousel-item-<?php echo (get_row_index() - 1); ?> .image-container {
                            background-image: url("<?php echo image_sizer($image_tablet, 578, 409, 'center', 'center'); ?>");
                        }
                    }
                    /* SM Breakpoint, retina */
                    @media (min-width: 768px) and (-webkit-min-device-pixel-ratio: 2), (min-width: 768px) and (min-resolution: 192dpi) {
                        #carousel-<?php echo esc_attr($id); ?> #carousel-item-<?php echo (get_row_index() - 1); ?> .image-container {
                            background-image: url("<?php echo image_sizer($image_tablet, 1156, 818, 'center', 'center'); ?>");
                        }
                    }
                    /* Desktop Image, Aspect ratio 1.8685:1 */
                    /* MD Breakpoint */
                    @media (min-width: 992px) {
                        #carousel-<?php echo esc_attr($id); ?> #carousel-item-<?php echo (get_row_index() - 1); ?> .image-container {
                            background-image: url("<?php echo image_sizer($image_desktop, 673, 360, 'center', 'center'); ?>");
                        }
                    }
                    /* MD Breakpoint, retina */
                    @media (min-width: 992px) and (-webkit-min-device-pixel-ratio: 2), (min-width: 992px) and (min-resolution: 192dpi) {
                        #carousel-<?php echo esc_attr($id); ?> #carousel-item-<?php echo (get_row_index() - 1); ?> .image-container {
                            background-image: url("<?php echo image_sizer($image_desktop, 1346, 720, 'center', 'center'); ?>");
                        }
                    }
                    /* LG Breakpoint */
                    @media (min-width: 1200px) {
                        #carousel-<?php echo esc_attr($id); ?> #carousel-item-<?php echo (get_row_index() - 1); ?> .image-container {
                            background-image: url("<?php echo image_sizer($image_desktop, 841, 450, 'center', 'center'); ?>");
                        }
                    }
                    /* LG Breakpoint, retina */
                    @media (min-width: 1200px) and (-webkit-min-device-pixel-ratio: 2), (min-width: 1200px) and (min-resolution: 192dpi) {
                        #carousel-<?php echo esc_attr($id); ?> #carousel-item-<?php echo (get_row_index() - 1); ?> .image-container {
                            background-image: url("<?php echo image_sizer($image_desktop, 1682, 900, 'center', 'center'); ?>");
                        }
                    }
                    /* XL Breakpoint */
                    @media (min-width: 1500px) {
                        #carousel-<?php echo esc_attr($id); ?> #carousel-item-<?php echo (get_row_index() - 1); ?> .image-container {
                            background-image: url("<?php echo image_sizer($image_desktop, 1076, 576, 'center', 'center'); ?>");
                        }
                    }
                    /* XL Breakpoint, retina */
                    @media (min-width: 1500px) and (-webkit-min-device-pixel-ratio: 2), (min-width: 1500px) and (min-resolution: 192dpi) {
                        #carousel-<?php echo esc_attr($id); ?> #carousel-item-<?php echo (get_row_index() - 1); ?> .image-container {
                            background-image: url("<?php echo image_sizer($image_desktop, 2152, 1152, 'center', 'center'); ?>");
                        }
                    }
                    /* XXL Breakpoint */
                    @media (min-width: 1500px) {
                        #carousel-<?php echo esc_attr($id); ?> #carousel-item-<?php echo (get_row_index() - 1); ?> .image-container {
                            background-image: url("<?php echo image_sizer($image_desktop, 1435, 768, 'center', 'center'); ?>");
                        }
                    }
                    /* XXL Breakpoint, retina */
                    @media (min-width: 1500px) and (-webkit-min-device-pixel-ratio: 2), (min-width: 1500px) and (min-resolution: 192dpi) {
                        #carousel-<?php echo esc_attr($id); ?> #carousel-item-<?php echo (get_row_index() - 1); ?> .image-container {
                            background-image: url("<?php echo image_sizer($image_desktop, 2870, 1536, 'center', 'center'); ?>");
                        }
                    }
                </style>
                <picture>
                    <!-- Mobile Image, Aspect ratio 16:9 -->
                    <source srcset="<?php echo image_sizer($image_mobile, 1536, 864, 'center', 'center'); ?>"
                        media="(min-width: 1px) and (-webkit-min-device-pixel-ratio: 2), 
                        (min-width: 1px) and (min-resolution: 192dpi)">
                    <source srcset="<?php echo image_sizer($image_mobile, 768, 432, 'center', 'center'); ?>"
                        media="(min-width: 1px)">
                    <source srcset="<?php echo image_sizer($image_mobile, 1152, 648, 'center', 'center'); ?>"
                        media="(min-width: 1px) and (-webkit-min-device-pixel-ratio: 2), 
                        (min-width: 1px) and (min-resolution: 192dpi)">
                    <source srcset="<?php echo image_sizer($image_mobile, 576, 324, 'center', 'center'); ?>"
                        media="(min-width: 1px)">
                    <!-- Fallback, use Tablet Image, Aspect ratio 1.4132:1 -->
                    <img src="<?php echo wp_get_attachment_url( $image_tablet, 'full' ); ?>" alt="<?php echo $image_alt; ?>" />
                </picture>
            </div>
            <div class="text-container">
                <div class="inner-container">
                    <h2><?php echo $heading; ?></h2>
                    <p><?php echo $body; ?></p>
                    <a class="btn" href="<?php echo $button_url; ?>" aria-label="<?php echo $button_desc; ?>"<?php echo $button_target ? ' target="_blank"' : ''; ?>><?php echo $button_text; ?></a>
                </div>
            </div>
        </div>
<?php
        endwhile;
?>
            </div><!-- .carousel-inner -->
<?php
        if($row_count > 1) { ?>
            <a class="carousel-control-prev" href="#carousel-<?php echo esc_attr($id); ?>" role="button" data-slide="prev">
                <span class="fas fa-angle-left" aria-hidden="true"></span>
                <span class="sr-only">Previous</span>
            </a>
            <a class="carousel-control-next" href="#carousel-<?php echo esc_attr($id); ?>" role="button" data-slide="next">
                <span class="fas fa-angle-right" aria-hidden="true"></span>
                <span class="sr-only">Next</span>
            </a>
        <?php   }  ?>
  </section>
<?php
endif;
