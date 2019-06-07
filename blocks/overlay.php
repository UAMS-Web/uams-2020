<?php
/*
 *
 * UAMS Text & Image Overlay Block
 * 
 */

// Create id attribute allowing for custom "anchor" value.
$id = 'text-image-overlay-' . $block['id'];

if( have_rows('overlay_section') ) {
    $rows = get_field('overlay_section');
    $row_count = count($rows);
} 

?>
<section class="uams-module no-padding text-image-overlay" id="<?php echo $id; ?>">
    <div class="container-fluid">
        <div class="row">
<?php 
    while ( have_rows('overlay_section') ) : the_row(); 
// Load values and assing defaults.
    $heading = get_sub_field('overlay_section_heading');
    $body = get_sub_field('overlay_section_body');
    $button_text = get_sub_field('overlay_section_button-text');
    $button_url = get_sub_field('overlay_section_button-url');
    $button_target = get_sub_field('overlay_section_button-target');
    $button_desc = get_sub_field('overlay_section_button-description');
    $background_color = get_sub_field('overlay_section_background-color');
    $image = get_sub_field('overlay_section_image');

?>
            <div class="col-12<?php echo $row_count > 1 ? " col-sm-6" : ""; ?> item bg-image item-1 <?php echo $background_color; ?>">
                <!-- Background styles for two tiles in one row -->
                <style>
                    #<?php echo $id; ?> .item-<?php echo get_row_index(); ?>:before {
                            background-image: url("<?php echo wp_get_attachment_image_url( $image, 'full' ); ?>");
                    }

                    /* XXS Breakpoint, retina */
                    @media (-webkit-min-device-pixel-ratio: 2),
                    (min-resolution: 192dpi) {
                        #<?php echo $id; ?> .item-<?php echo get_row_index(); ?>:before {
                            background-image: url("<?php echo wp_get_attachment_image_url( $image, 'full' ); ?>");
                        }
                    }

                    /* XS Breakpoint */
                    @media (min-width: 576px) {
                        #<?php echo $id; ?> .item-<?php echo get_row_index(); ?>:before {
                            background-image: url("<?php echo wp_get_attachment_image_url( $image, 'full' ); ?>");
                        }
                    }

                    /* XS Breakpoint, retina */
                    @media (min-width: 576px) and (-webkit-min-device-pixel-ratio: 2),
                    (min-width: 576px) and (min-resolution: 192dpi) {
                        #<?php echo $id; ?> .item-<?php echo get_row_index(); ?>:before {
                            background-image: url("<?php echo wp_get_attachment_image_url( $image, 'full' ); ?>");
                        }
                    }

                    /* SM Breakpoint */
                    @media (min-width: 768px) {
                        #<?php echo $id; ?> .item-<?php echo get_row_index(); ?>:before {
                            background-image: url("<?php echo wp_get_attachment_image_url( $image, 'full' ); ?>");
                        }
                    }

                    /* SM Breakpoint, retina */
                    @media (min-width: 768px) and (-webkit-min-device-pixel-ratio: 2),
                    (min-width: 768px) and (min-resolution: 192dpi) {
                        #<?php echo $id; ?> .item-<?php echo get_row_index(); ?>:before {
                            background-image: url("<?php echo wp_get_attachment_image_url( $image, 'full' ); ?>");
                        }
                    }

                    /* MD Breakpoint */
                    @media (min-width: 992px) {
                        #<?php echo $id; ?> .item-<?php echo get_row_index(); ?>:before {
                            background-image: url("<?php echo wp_get_attachment_image_url( $image, 'full' ); ?>");
                        }
                    }

                    /* MD Breakpoint, retina */
                    @media (min-width: 992px) and (-webkit-min-device-pixel-ratio: 2),
                    (min-width: 992px) and (min-resolution: 192dpi) {
                        #<?php echo $id; ?> .item-<?php echo get_row_index(); ?>:before {
                            background-image: url("<?php echo wp_get_attachment_image_url( $image, 'full' ); ?>");
                        }
                    }

                    /* LG Breakpoint */
                    @media (min-width: 1200px) {
                        #<?php echo $id; ?> .item-<?php echo get_row_index(); ?>:before {
                            background-image: url("<?php echo wp_get_attachment_image_url( $image, 'full' ); ?>");
                        }
                    }

                    /* LG Breakpoint, retina */
                    @media (min-width: 1200px) and (-webkit-min-device-pixel-ratio: 2),
                    (min-width: 1200px) and (min-resolution: 192dpi) {
                        #<?php echo $id; ?> .item-<?php echo get_row_index(); ?>:before {
                            background-image: url("<?php echo wp_get_attachment_image_url( $image, 'full' ); ?>");
                        }
                    }

                    /* XL Breakpoint */
                    @media (min-width: 1500px) {
                        #<?php echo $id; ?> .item-<?php echo get_row_index(); ?>:before {
                            background-image: url("<?php echo wp_get_attachment_image_url( $image, 'full' ); ?>");
                        }
                    }

                    /* XL Breakpoint, retina */
                    @media (min-width: 1500px) and (-webkit-min-device-pixel-ratio: 2),
                    (min-width: 1500px) and (min-resolution: 192dpi) {
                        #<?php echo $id; ?> .item-<?php echo get_row_index(); ?>:before {
                            background-image: url("<?php echo wp_get_attachment_image_url( $image, 'full' ); ?>");
                        }
                    }

                    /* XXL Breakpoint */
                    @media (min-width: 1500px) {
                        #<?php echo $id; ?> .item-<?php echo get_row_index(); ?>:before {
                            background-image: url("<?php echo wp_get_attachment_image_url( $image, 'full' ); ?>");
                        }
                    }

                    /* XXL Breakpoint, retina */
                    @media (min-width: 1500px) and (-webkit-min-device-pixel-ratio: 2),
                    (min-width: 1500px) and (min-resolution: 192dpi) {
                        #<?php echo $id; ?> .item-<?php echo get_row_index(); ?>:before {
                            background-image: url("<?php echo wp_get_attachment_image_url( $image, 'full' ); ?>");
                        }
                    }
                </style>
                <div class="text-container">
                    <h2><?php echo $heading; ?></h2>
                    <p><?php echo $body; ?></p>
                    <a href="<?php echo $button_url; ?>" aria-label="<?php echo $button_desc; ?>" class="btn"<?php echo $button_target ? ' target="_blank"' : ''; ?>><?php echo $button_text; ?></a>
                </div>
            </div>
<?php
    endwhile;
?>
        </div>
    </div>
</section>