<?php
/*
 *
 * UAMS Stacked Image & Text Block
 * 
 */

// Create id attribute allowing for custom "anchor" value.
$id = 'stacked-image-text-' . $block['id'];

// Load values.
$heading = get_field('stacked_heading');
$hide_heading = get_field('stacked_hide-heading');
$background_color = get_field('stacked_background-color');

if( have_rows('stacked_section') ) {
    $rows = get_field('stacked_section');
    $row_count = count($rows);
} 

?>
<section class="uams-module stacked-image-text <?php echo $background_color; ?>" id="<?php echo $id; ?>">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12<?php echo $hide_heading ? " sr-only" : ""; ?>">
                <h2 class="module-title"><span class="title"><?php echo $heading; ?></span></h2>
            </div>
            <?php 
                while ( have_rows('stacked_section') ) : the_row(); 
                // Load values.
                $image = get_sub_field('stacked_section_image');
                $image_alt_native = get_post_meta($image, '_wp_attachment_image_alt', TRUE);
                $image_alt_override = get_sub_field('stacked_section_alt-override');
                $item_heading = get_sub_field('stacked_section_heading');
                $body = get_sub_field('stacked_section_body');
                $button_text = get_sub_field('stacked_section_button-text');
                $button_url = get_sub_field('stacked_section_button-url');
                $button_target = get_sub_field('stacked_section_button-target');
                $button_desc = get_sub_field('stacked_section_button-description');

            ?>
            <div class="col-12 col-sm-6 col-xl-3 item">
                <div class="card">
                    <div class="card-img-top">
                        <picture>
                            <source srcset="<?php echo wp_get_attachment_image_url( $image, 'full' ); ?>" media="(min-width: 1px) and (-webkit-min-device-pixel-ratio: 2), 
                                (min-width: 1px) and (min-resolution: 192dpi)">
                            <source srcset="<?php echo wp_get_attachment_image_url( $image, 'full' ); ?>" media="(min-width: 1px)">
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
                endwhile;
            ?>
        </div>
    </div>
</section>