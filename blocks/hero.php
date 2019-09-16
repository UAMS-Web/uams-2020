<?php 
/*
 *
 * UAMS Hero Block
 * 
 */
// Create id attribute allowing for custom "anchor" value.
$id = '';
if ( empty( $id ) && isset($block) )
    $id = $block['id'];

$id = 'hero-' . $id;

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

if ( empty($hero_rows) )
    $hero_rows = get_field('hero');

if( $hero_rows ) :
    $row_count = count($hero_rows);

?>
    <section class="hero carousel slide<?php echo $row_count > 1 ? " multiple-slides" : ""; ?>" id="carousel-<?php echo esc_attr($id); ?>">
<?php
        // $page_template = get_page_template_slug( $post_id );
        // echo $page_template;
        // if ('templates/page_landing.php' != $page_template && 'templates/modules.php' != $page_template) {
        //     echo '<h4>This template is not supported. Please select "Landing"</h4>';
        //     return;
        // }
        
        if($row_count > 1) { ?>
            <ol class="carousel-indicators">
                <?php for ($i = 0; $i < $row_count; $i++) { ?>
                <li data-target="#carousel-<?php echo esc_attr($id) ?>" data-slide-to="<?php echo $i; ?>" <?php echo (0 == $i ? 'class="active"' : ''); ?>></li>
                <?php } ?>
            </ol>
<?php   }  ?>
    <div class="carousel-inner <?php echo esc_attr($className); ?>">
<?php 
    $index = 1;
    foreach($hero_rows as $hero_row) {
// Load values and adding defaults.
    // if ( empty($heading) )
        $heading = $hero_row['hero_heading'] ?: 'Heading goes here...';
    // if ( empty($body) )
        $body = $hero_row['hero_body'] ?: 'This is where the description goes';
    // if ( empty($button_text) )
        $button_text = $hero_row['hero_button_text'] ?: 'Learn More';
    // if ( empty($button_url) )
        $button_url = $hero_row['hero_button_url']['url'] ?: '';
    // if ( empty($button_target) )
        $button_target = $hero_row['hero_button_url']['target'] ?: '';
    // if ( empty($button_desc) )
        $button_desc = $hero_row['hero_button_description'] ?: '';
    // if ( empty($image_desktop) )
        $image_desktop = $hero_row['hero_image_desktop'] ?: ''; // Required
    // if ( empty($image_tablet) )
        $image_tablet = $hero_row['hero_image_tablet'] ?: '';
    // if ( empty($image_mobile) )
        $image_mobile = $hero_row['hero_image_mobile'] ?: '';
    // if ( empty($image_alt) )
        $image_alt = $hero_row['hero_image_alt_text'] ?: '';
    // if ( empty($background_color) )
        $background_color = $hero_row['hero_background_color'] ?: 'auto';

    // Load values for if bg auto
    $background_color_1 = 'red';
    $background_color_2 = 'blue';
    $background_color_3 = 'green';
    $background_color_4 = 'eggplant';

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
        <div class="carousel-item <?php 
        if ($background_color == 'auto') {
            if ($index == 1) {
                echo $background_color_1;
            } elseif ($index == 2) {
                echo $background_color_2;
            } elseif ($index == 3) {
                echo $background_color_3;
            } else {
                echo $background_color_4;
            }
        } else {
            echo $background_color;
        }
        ?><?php echo (0 == ($index - 1) ? ' active' : ''); ?>" id="carousel-item-<?php echo ($index - 1); ?>">
            <div class="image-container">
                <picture>
                    <?php if ( function_exists( 'fly_add_image_size' ) ) { ?>
                    <!-- Desktop Image, Aspect ratio 1.8685:1 -->
                    <source srcset="<?php echo image_sizer($image_desktop, 2870, 1536, 'center', 'center'); ?>"
                        media="(min-width: 1921px) and (-webkit-min-device-pixel-ratio: 2), 
                        (min-width: 1921px) and (min-resolution: 192dpi)">
                    <source srcset="<?php echo image_sizer($image_desktop, 1435, 768, 'center', 'center'); ?>"
                        media="(min-width: 1921px)">
                    <source srcset="<?php echo image_sizer($image_desktop, 2152, 1152, 'center', 'center'); ?>"
                        media="(min-width: 1500px) and (-webkit-min-device-pixel-ratio: 2), 
                        (min-width: 1500px) and (min-resolution: 192dpi)">
                    <source srcset="<?php echo image_sizer($image_desktop, 1076, 576, 'center', 'center'); ?>"
                        media="(min-width: 1500px)">
                    <source srcset="<?php echo image_sizer($image_desktop, 1682, 900, 'center', 'center'); ?>"
                        media="(min-width: 1200px) and (-webkit-min-device-pixel-ratio: 2), 
                        (min-width: 1200px) and (min-resolution: 192dpi)">
                    <source srcset="<?php echo image_sizer($image_desktop, 841, 450, 'center', 'center'); ?>"
                        media="(min-width: 1200px)">
                    <source srcset="<?php echo image_sizer($image_desktop, 1346, 720, 'center', 'center'); ?>"
                        media="(min-width: 992px) and (-webkit-min-device-pixel-ratio: 2), 
                        (min-width: 992px) and (min-resolution: 192dpi)">
                    <source srcset="<?php echo image_sizer($image_desktop, 673, 360, 'center', 'center'); ?>"
                        media="(min-width: 992px)">
                    <!-- Tablet Image, Aspect ratio 1.4132:1 -->
                    <source srcset="<?php echo image_sizer($image_tablet, 1156, 818, 'center', 'center'); ?>"
                        media="(min-width: 768px) and (-webkit-min-device-pixel-ratio: 2), 
                        (min-width: 768px) and (min-resolution: 192dpi)">
                    <source srcset="<?php echo image_sizer($image_tablet, 578, 409, 'center', 'center'); ?>"
                        media="(min-width: 768px)">
                    <!-- Mobile Image, Aspect ratio 16:9 -->
                    <source srcset="<?php echo image_sizer($image_mobile, 1536, 864, 'center', 'center'); ?>"
                        media="(min-width: 576px) and (-webkit-min-device-pixel-ratio: 2), 
                        (min-width: 576px) and (min-resolution: 192dpi)">
                    <source srcset="<?php echo image_sizer($image_mobile, 768, 432, 'center', 'center'); ?>"
                        media="(min-width: 576px)">
                    <source srcset="<?php echo image_sizer($image_mobile, 1152, 648, 'center', 'center'); ?>"
                        media="(min-width: 1px) and (-webkit-min-device-pixel-ratio: 2), 
                        (min-width: 1px) and (min-resolution: 192dpi)">
                    <source srcset="<?php echo image_sizer($image_mobile, 576, 324, 'center', 'center'); ?>"
                        media="(min-width: 1px)">
                    <!-- Fallback -->
                    <img src="<?php echo image_sizer($image_tablet, 455, 256, 'center', 'center'); ?>" alt="<?php echo $image_alt; ?>" />
                    <?php } else { ?>
                    <!-- Fallback -->
                    <img src="<?php echo wp_get_attachment_image_url( $image_tablet, 'hero-tablet' ); ?>" alt="<?php echo $image_alt; ?>" />
                    <?php } //endif ?>
                </picture>
            </div>
            <div class="text-container">
                <div class="inner-container">
                    <h2><?php echo $heading; ?></h2>
                    <p><?php echo $body; ?></p>
                    <a class="btn" href="<?php echo $button_url; ?>" aria-label="<?php echo $button_desc; ?>"<?php echo $button_target ? ' target="'. $button_target .'"' : ''; ?>><?php echo $button_text; ?></a>
                </div>
            </div>
        </div>
<?php
            $index++;
        } // end foreach
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
