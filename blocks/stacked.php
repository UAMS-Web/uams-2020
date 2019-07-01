<?php
/*
 *
 * UAMS Stacked Image & Text Block
 * 
 */

// Create id attribute allowing for custom "anchor" value.
if ( empty( $id ) )
    $id = 'stacked-image-text-' . $block['id'];

// Load values.
if ( empty($heading) )
    $heading = get_field('stacked_heading');
if ( empty($hide_heading) )
    $hide_heading = get_field('stacked_hide_heading');
if ( empty($background_color) )
    $background_color = get_field('stacked_background_color');
if ( empty($stacked_rows) )
    $stacked_rows = get_field('stacked_section');

if( $stacked_rows ) :
    $row_count = count($stacked_rows); // Not user, but just in case

?>
<section class="uams-module stacked-image-text <?php echo $background_color; ?>" id="<?php echo $id; ?>">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12<?php echo $hide_heading ? " sr-only" : ""; ?>">
                <h2 class="module-title"><span class="title"><?php echo $heading; ?></span></h2>
            </div>
            <?php 
                foreach($stacked_rows as $stacked_row) {
                // Load values.
                $image = $stacked_row['stacked_section_image'];
                $image_alt_native = get_post_meta($image, '_wp_attachment_image_alt', TRUE);
                $image_alt_override = $stacked_row['stacked_section_alt_override'];
                $item_heading = $stacked_row['stacked_section_heading'];
                $body = $stacked_row['stacked_section_body'];
                $button_text = $stacked_row['stacked_section_button_text'];
                $button_url = $stacked_row['stacked_section_button_url'];
                $button_target = $stacked_row['stacked_section_button_target'];
                $button_desc = $stacked_row['stacked_section_button_description'];

            ?>
            <div class="col-12 col-sm-6 col-xl-3 item">
                <div class="card">
                    <div class="card-img-top">
                        <picture>
                            <?php if ( function_exists( 'fly_add_image_size' ) ) { ?>  
                            <source srcset="<?php echo image_sizer($image, 910, 512, 'center', 'center'); ?>" 
                                media="(min-width: 1921px) and (-webkit-min-device-pixel-ratio: 2), 
                                (min-width: 1921px) and (min-resolution: 192dpi)">
                            <source srcset="<?php echo image_sizer($image, 455, 256, 'center', 'center'); ?>" 
                                media="(min-width: 1921px)">
                            <source srcset="<?php echo image_sizer($image, 866, 487, 'center', 'center'); ?>" 
                                media="(min-width: 1500px) and (-webkit-min-device-pixel-ratio: 2), 
                                (min-width: 1500px) and (min-resolution: 192dpi)">
                            <source srcset="<?php echo image_sizer($image, 433, 244, 'center', 'center'); ?>" 
                                media="(min-width: 1500px)">
                            <source srcset="<?php echo image_sizer($image, 910, 512, 'center', 'center'); ?>" 
                                media="(min-width: 992px) and (-webkit-min-device-pixel-ratio: 2), 
                                (min-width: 992px) and (min-resolution: 192dpi)">
                            <source srcset="<?php echo image_sizer($image, 455, 256, 'center', 'center'); ?>" 
                                media="(min-width: 992px)">
                            <source srcset="<?php echo image_sizer($image, 866, 487, 'center', 'center'); ?>" 
                                media="(min-width: 768px) and (-webkit-min-device-pixel-ratio: 2), 
                                (min-width: 768px) and (min-resolution: 192dpi)">
                            <source srcset="<?php echo image_sizer($image, 433, 244, 'center', 'center'); ?>" 
                                media="(min-width: 768px)">
                            <source srcset="<?php echo image_sizer($image, 910, 512, 'center', 'center'); ?>" 
                                media="(min-width: 1px) and (-webkit-min-device-pixel-ratio: 2), 
                                (min-width: 1px) and (min-resolution: 192dpi)">
                            <source srcset="<?php echo image_sizer($image, 455, 256, 'center', 'center'); ?>" 
                                media="(min-width: 1px)">
                            <?php } //endif ?>
                            <!-- Fallback -->
                            <img src="<?php echo wp_get_attachment_image_url( $image, 'full' ); ?>" alt="<?php echo $image_alt_override ? $image_alt_override : $image_alt_native; ?>" />
                        </picture>
                    </div>
                    <div class="card-body">
                        <h3 class="card-title h5"><?php echo $item_heading; ?></h3>
                        <p class="card-text"><?php echo $body; ?></p>
                        <a href="<?php echo $button_url; ?>" class="btn btn-primary stretched-link" aria-label="<?php echo $button_desc; ?>"<?php echo $button_target ? ' target="_blank"' : ''; ?>><?php echo $button_text; ?></a>
                    </div>
                </div>
            </div>
            <?php
                }
            ?>
        </div>
    </div>
</section>
<?php endif;