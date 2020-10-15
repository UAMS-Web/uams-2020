<?php 
/*
 *
 * UAMS Hero Block
 * 
 */
// Create id attribute allowing for custom "anchor" value.
$id = '';
if ( empty( $id ) && isset($block) ) {
    $id = $block['id'];
} 
if ( empty ($id) ) {
    $id = !empty( $module['anchor_id'] ) ? sanitize_title_with_dashes( $module['anchor_id'] ) : 'module-' . ( $i + 1 );
}

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

$char_per_word = 5.9; // Average calculated using blindtextgenerator.com
$char_heading = 79; // Character limit of slide heading
$char_body = 177; // Character limit of slide body
$char_button = 29; // Character limit of slide button
$char_total = $char_heading + $char_body + $char_button;
$word_est = $char_total / $char_per_word; // Estimate of total words in slide
$wpm = 214; // National average for optimal silent reading rate for 9th grade, as words per minute (Hasbrouck & Tindal, 2006)
$wps = $wpm / 60; // words per second
$read_time = $word_est / $wps; // Time to read total words
$slide_time = round(($read_time + 2) * 1000, 0); // 1 second to find place + time to read + 1 second to interact (in milliseconds)


?>
    <section class="hero carousel <?php echo esc_attr($className); ?> slide<?php echo $row_count > 1 ? " multiple-slides" : ""; ?>" id="carousel-<?php echo esc_attr($id); ?>" aria-label="Hero banner"<?php echo $row_count > 1 ? ' data-ride="carousel" data-interval="' . $slide_time . '" data-keyboard="true"' : ''; ?>>
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
    <div class="carousel-inner">
<?php 
    $index = 1;
    foreach($hero_rows as $hero_row) {
// Load values and adding defaults.
    // if ( empty($disable) )
        $disable = $hero_row['hero_disable'] ?: '';
    // if ( empty($heading) )
        $heading = $hero_row['hero_heading'] ?: 'Heading goes here...'; // Required
    // if ( empty($body) )
        $body = $hero_row['hero_body'] ?: 'This is where the description goes'; // Required
    // if ( empty($button_text) )
        $button_text = $hero_row['hero_button_text'] ?: '';
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
    if ( !$disable ) {
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
                    <source srcset="<?php echo image_sizer($image_desktop, 1435, 768, 'center', 'center'); ?> 1x, <?php echo image_sizer($image_desktop, 2870, 1536, 'center', 'center'); ?> 2x"
                        media="(min-width: 1921px)">
                    <source srcset="<?php echo image_sizer($image_desktop, 1076, 576, 'center', 'center'); ?> 1x, <?php echo image_sizer($image_desktop, 2152, 1152, 'center', 'center'); ?> 2x"
                        media="(min-width: 1500px)">
                    <source srcset="<?php echo image_sizer($image_desktop, 841, 450, 'center', 'center'); ?> 1x, <?php echo image_sizer($image_desktop, 1682, 900, 'center', 'center'); ?> 2x"
                        media="(min-width: 1200px)">
                    <source srcset="<?php echo image_sizer($image_desktop, 673, 360, 'center', 'center'); ?> 1x, <?php echo image_sizer($image_desktop, 1346, 720, 'center', 'center'); ?> 2x"
                        media="(min-width: 992px)">
                    <!-- Tablet Image, Aspect ratio 1.4132:1 -->
                    <source srcset="<?php echo image_sizer($image_tablet, 578, 409, 'center', 'center'); ?> 1x, <?php echo image_sizer($image_tablet, 1156, 818, 'center', 'center'); ?> 2x"
                        media="(min-width: 768px)">
                    <!-- Mobile Image, Aspect ratio 16:9 -->
                    <source srcset="<?php echo image_sizer($image_mobile, 768, 432, 'center', 'center'); ?> 1x, <?php echo image_sizer($image_mobile, 1536, 864, 'center', 'center'); ?> 2x"
                        media="(min-width: 576px)">
                    <source srcset="<?php echo image_sizer($image_mobile, 576, 324, 'center', 'center'); ?> 1x, <?php echo image_sizer($image_mobile, 1152, 648, 'center', 'center'); ?> 2x"
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
                    <?php if ($button_text) { ?>
                        <a class="btn btn-white" href="<?php echo $button_url; ?>" aria-label="<?php echo $button_desc; ?>"<?php echo $button_target ? ' target="'. $button_target .'"' : ''; ?> data-itemtitle="<?php echo $heading; ?>"><?php echo $button_text; ?></a>
                    <?php } // endif ?>
                </div>
            </div>
        </div>
<?php } // endif
            $index++;
        } // end foreach
?>
            </div><!-- .carousel-inner -->
<?php
        if($row_count > 1) { ?>
            <a class="carousel-control-prev" href="#carousel-<?php echo esc_attr($id); ?>" role="button" data-slide="prev">
                <span class="fas fa-angle-left"></span>
                <span class="sr-only">Previous</span>
            </a>
            <a class="carousel-control-next" href="#carousel-<?php echo esc_attr($id); ?>" role="button" data-slide="next">
                <span class="fas fa-angle-right"></span>
                <span class="sr-only">Next</span>
            </a>
        <?php   }  ?>
  </section>
<?php
endif;
