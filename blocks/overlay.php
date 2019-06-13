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
                <?php if ( $row_count > 1 && function_exists( 'fly_add_image_size' ) ) { // Background styles for two tiles in one row with Fly plugin ?>
                <style>
                    #<?php echo $id; ?> .item-<?php echo get_row_index(); ?>:before {
                        background-image: url("<?php echo image_sizer($image, 576, 432, 'center', 'center'); ?>");
                    }

                    /* XXS Breakpoint, retina */
                    @media (-webkit-min-device-pixel-ratio: 2),
                    (min-resolution: 192dpi) {
                        #<?php echo $id; ?> .item-<?php echo get_row_index(); ?>:before {
                            background-image: url("<?php echo image_sizer($image, 1152, 864, 'center', 'center'); ?>");
                        }
                    }

                    /* XS Breakpoint */
                    @media (min-width: 576px) {
                        #<?php echo $id; ?> .item-<?php echo get_row_index(); ?>:before {
                            background-image: url("<?php echo image_sizer($image, 768, 576, 'center', 'center'); ?>");
                        }
                    }

                    /* XS Breakpoint, retina */
                    @media (min-width: 576px) and (-webkit-min-device-pixel-ratio: 2),
                    (min-width: 576px) and (min-resolution: 192dpi) {
                        #<?php echo $id; ?> .item-<?php echo get_row_index(); ?>:before {
                            background-image: url("<?php echo image_sizer($image, 1536, 1152, 'center', 'center'); ?>");
                        }
                    }

                    /* SM Breakpoint */
                    @media (min-width: 768px) {
                        #<?php echo $id; ?> .item-<?php echo get_row_index(); ?>:before {
                            background-image: url("<?php echo image_sizer($image, 496, 372, 'center', 'center'); ?>");
                        }
                    }

                    /* SM Breakpoint, retina */
                    @media (min-width: 768px) and (-webkit-min-device-pixel-ratio: 2),
                    (min-width: 768px) and (min-resolution: 192dpi) {
                        #<?php echo $id; ?> .item-<?php echo get_row_index(); ?>:before {
                            background-image: url("<?php echo image_sizer($image, 992, 744, 'center', 'center'); ?>");
                        }
                    }

                    /* MD Breakpoint */
                    @media (min-width: 992px) {
                        #<?php echo $id; ?> .item-<?php echo get_row_index(); ?>:before {
                            background-image: url("<?php echo image_sizer($image, 600, 450, 'center', 'center'); ?>");
                        }
                    }

                    /* MD Breakpoint, retina */
                    @media (min-width: 992px) and (-webkit-min-device-pixel-ratio: 2),
                    (min-width: 992px) and (min-resolution: 192dpi) {
                        #<?php echo $id; ?> .item-<?php echo get_row_index(); ?>:before {
                            background-image: url("<?php echo image_sizer($image, 1200, 900, 'center', 'center'); ?>");
                        }
                    }

                    /* LG Breakpoint */
                    @media (min-width: 1200px) {
                        #<?php echo $id; ?> .item-<?php echo get_row_index(); ?>:before {
                            background-image: url("<?php echo image_sizer($image, 750, 563, 'center', 'center'); ?>");
                        }
                    }

                    /* LG Breakpoint, retina */
                    @media (min-width: 1200px) and (-webkit-min-device-pixel-ratio: 2),
                    (min-width: 1200px) and (min-resolution: 192dpi) {
                        #<?php echo $id; ?> .item-<?php echo get_row_index(); ?>:before {
                            background-image: url("<?php echo image_sizer($image, 1500, 1125, 'center', 'center'); ?>");
                        }
                    }

                    /* XL Breakpoint */
                    @media (min-width: 1500px) {
                        #<?php echo $id; ?> .item-<?php echo get_row_index(); ?>:before {
                            background-image: url("<?php echo image_sizer($image, 961, 720, 'center', 'center'); ?>");
                        }
                    }

                    /* XL Breakpoint, retina */
                    @media (min-width: 1500px) and (-webkit-min-device-pixel-ratio: 2),
                    (min-width: 1500px) and (min-resolution: 192dpi) {
                        #<?php echo $id; ?> .item-<?php echo get_row_index(); ?>:before {
                            background-image: url("<?php echo image_sizer($image, 1921, 1441, 'center', 'center'); ?>");
                        }
                    }

                    /* XXL Breakpoint */
                    @media (min-width: 1921px) {
                        #<?php echo $id; ?> .item-<?php echo get_row_index(); ?>:before {
                            background-image: url("<?php echo image_sizer($image, 1280, 960, 'center', 'center'); ?>");
                        }
                    }

                    /* XXL Breakpoint, retina */
                    @media (min-width: 1921px) and (-webkit-min-device-pixel-ratio: 2),
                    (min-width: 1921px) and (min-resolution: 192dpi) {
                        #<?php echo $id; ?> .item-<?php echo get_row_index(); ?>:before {
                            background-image: url("<?php echo image_sizer($image, 2560, 1920, 'center', 'center'); ?>");
                        }
                    }
                </style>
                <?php } elseif ( function_exists( 'fly_add_image_size' ) ) { // Background styles for one tile in one row with Fly plugin ?>
                <style>
                    #<?php echo $id; ?> .item-<?php echo get_row_index(); ?>:before {
                        background-image: url("<?php echo image_sizer($image, 576, 432, 'center', 'center'); ?>");
                    }

                    /* XS Breakpoint, retina */
                    @media (-webkit-min-device-pixel-ratio: 2),
                    (min-resolution: 192dpi) {
                        #<?php echo $id; ?> .item-<?php echo get_row_index(); ?>:before {
                            background-image: url("<?php echo image_sizer($image, 1152, 864, 'center', 'center'); ?>");
                        }
                    }

                    /* XS Breakpoint */
                    @media (min-width: 576px) {
                        #<?php echo $id; ?> .item-<?php echo get_row_index(); ?>:before {
                            background-image: url("<?php echo image_sizer($image, 768, 576, 'center', 'center'); ?>");
                        }
                    }

                    /* XS Breakpoint, retina */
                    @media (min-width: 576px) and (-webkit-min-device-pixel-ratio: 2),
                    (min-width: 576px) and (min-resolution: 192dpi) {
                        #<?php echo $id; ?> .item-<?php echo get_row_index(); ?>:before {
                            background-image: url("<?php echo image_sizer($image, 1536, 1152, 'center', 'center'); ?>");
                        }
                    }

                    /* SM Breakpoint */
                    @media (min-width: 768px) {
                        #<?php echo $id; ?> .item-<?php echo get_row_index(); ?>:before {
                            background-image: url("<?php echo image_sizer($image, 992, 744, 'center', 'center'); ?>");
                        }
                    }

                    /* SM Breakpoint, retina */
                    @media (min-width: 768px) and (-webkit-min-device-pixel-ratio: 2),
                    (min-width: 768px) and (min-resolution: 192dpi) {
                        #<?php echo $id; ?> .item-<?php echo get_row_index(); ?>:before {
                            background-image: url("<?php echo image_sizer($image, 1984, 1488, 'center', 'center'); ?>");
                        }
                    }

                    /* MD Breakpoint */
                    @media (min-width: 992px) {
                        #<?php echo $id; ?> .item-<?php echo get_row_index(); ?>:before {
                            background-image: url("<?php echo image_sizer($image, 1200, 900, 'center', 'center'); ?>");
                        }
                    }

                    /* MD Breakpoint, retina */
                    @media (min-width: 992px) and (-webkit-min-device-pixel-ratio: 2),
                    (min-width: 992px) and (min-resolution: 192dpi) {
                        #<?php echo $id; ?> .item-<?php echo get_row_index(); ?>:before {
                            background-image: url("<?php echo image_sizer($image, 2400, 1800, 'center', 'center'); ?>");
                        }
                    }

                    /* LG Breakpoint */
                    @media (min-width: 1200px) {
                        #<?php echo $id; ?> .item-<?php echo get_row_index(); ?>:before {
                            background-image: url("<?php echo image_sizer($image, 1500, 1125, 'center', 'center'); ?>");
                        }
                    }

                    /* LG Breakpoint, retina */
                    @media (min-width: 1200px) and (-webkit-min-device-pixel-ratio: 2),
                    (min-width: 1200px) and (min-resolution: 192dpi) {
                        #<?php echo $id; ?> .item-<?php echo get_row_index(); ?>:before {
                            background-image: url("<?php echo image_sizer($image, 3000, 2250, 'center', 'center'); ?>");
                        }
                    }

                    /* XL Breakpoint */
                    @media (min-width: 1500px) {
                        #<?php echo $id; ?> .item-<?php echo get_row_index(); ?>:before {
                            background-image: url("<?php echo image_sizer($image, 1921, 1441, 'center', 'center'); ?>");
                        }
                    }

                    /* XL Breakpoint, retina */
                    @media (min-width: 1500px) and (-webkit-min-device-pixel-ratio: 2),
                    (min-width: 1500px) and (min-resolution: 192dpi) {
                        #<?php echo $id; ?> .item-<?php echo get_row_index(); ?>:before {
                            background-image: url("<?php echo image_sizer($image, 3842, 2882, 'center', 'center'); ?>");
                        }
                    }

                    /* XXL Breakpoint */
                    @media (min-width: 1921px) {
                        #<?php echo $id; ?> .item-<?php echo get_row_index(); ?>:before {
                            background-image: url("<?php echo image_sizer($image, 2560, 1920, 'center', 'center'); ?>");
                        }
                    }

                    /* XXL Breakpoint, retina */
                    @media (min-width: 1921px) and (-webkit-min-device-pixel-ratio: 2),
                    (min-width: 1921px) and (min-resolution: 192dpi) {
                        #<?php echo $id; ?> .item-<?php echo get_row_index(); ?>:before {
                            background-image: url("<?php echo image_sizer($image, 5120, 3840, 'center', 'center'); ?>");
                        }
                    }
                </style>
                <?php } else { // Background styles for no Fly plugin ?>
                <style>
                    #<?php echo $id; ?> .item-<?php echo get_row_index(); ?>:before {
                            background-image: url("<?php echo wp_get_attachment_image_url( $image, 'full' ); ?>");
                    }
                </style>
                <?php } //endif ?>
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