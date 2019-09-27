<?php
/*
 *
 * UAMS Text & Image Overlay Block
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
    
$id = 'text-image-overlay-' . $id;

$className = '';
if( !empty($block['className']) ) {
    $className .= ' ' . $block['className'];
}
if( !empty($block['align']) ) {
    $className .= ' align' . $block['align'];
}


if( empty($overlay_rows) )
    $overlay_rows = get_field('overlay_section');

if( $overlay_rows ) :
    $row_count = count($overlay_rows);


?>
<div class="uams-module no-padding text-image-overlay" id="<?php echo $id; ?>">
    <div class="container-fluid">
        <div class="row">
<?php 
    $index = 1;
    foreach($overlay_rows as $overlay_row) { 
        // Load values and adding defaults.
        $heading = $overlay_row['overlay_section_heading'];
        $body = $overlay_row['overlay_section_body'];
        $button_text = $overlay_row['overlay_section_button_text'];
        $button_url = $overlay_row['overlay_section_button_url']['url'];
        $button_target = $overlay_row['overlay_section_button_url']['target'];
        $button_desc = $overlay_row['overlay_section_button_description'];
        $background_color = $overlay_row['overlay_section_background_color'];
        $image = $overlay_row['overlay_section_image'];

?>
            <section class="col-12<?php echo $row_count > 1 ? " col-sm-6" : ""; ?> item bg-image<?php echo $className; ?> item-<?php echo $index; ?> <?php echo $background_color; ?>" aria-label="<?php echo $heading; ?>">
                <?php if ( $row_count > 1 && function_exists( 'fly_add_image_size' ) ) { // Background styles for two tiles in one row with Fly plugin ?>
                <style>
                    #<?php echo $id; ?> .item-<?php echo $index; ?>:before {
                        background-image: url("<?php echo image_sizer($image, 576, 432, 'center', 'center'); ?>");
                    }

                    /* XXS Breakpoint, retina */
                    @media (-webkit-min-device-pixel-ratio: 2),
                    (min-resolution: 192dpi) {
                        #<?php echo $id; ?> .item-<?php echo $index; ?>:before {
                            background-image: url("<?php echo image_sizer($image, 1152, 864, 'center', 'center'); ?>");
                        }
                    }

                    /* XS Breakpoint */
                    @media (min-width: 576px) {
                        #<?php echo $id; ?> .item-<?php echo $index; ?>:before {
                            background-image: url("<?php echo image_sizer($image, 768, 576, 'center', 'center'); ?>");
                        }
                    }

                    /* XS Breakpoint, retina */
                    @media (min-width: 576px) and (-webkit-min-device-pixel-ratio: 2),
                    (min-width: 576px) and (min-resolution: 192dpi) {
                        #<?php echo $id; ?> .item-<?php echo $index; ?>:before {
                            background-image: url("<?php echo image_sizer($image, 1536, 1152, 'center', 'center'); ?>");
                        }
                    }

                    /* SM Breakpoint */
                    @media (min-width: 768px) {
                        #<?php echo $id; ?> .item-<?php echo $index; ?>:before {
                            background-image: url("<?php echo image_sizer($image, 496, 372, 'center', 'center'); ?>");
                        }
                    }

                    /* SM Breakpoint, retina */
                    @media (min-width: 768px) and (-webkit-min-device-pixel-ratio: 2),
                    (min-width: 768px) and (min-resolution: 192dpi) {
                        #<?php echo $id; ?> .item-<?php echo $index; ?>:before {
                            background-image: url("<?php echo image_sizer($image, 992, 744, 'center', 'center'); ?>");
                        }
                    }

                    /* MD Breakpoint */
                    @media (min-width: 992px) {
                        #<?php echo $id; ?> .item-<?php echo $index; ?>:before {
                            background-image: url("<?php echo image_sizer($image, 600, 450, 'center', 'center'); ?>");
                        }
                    }

                    /* MD Breakpoint, retina */
                    @media (min-width: 992px) and (-webkit-min-device-pixel-ratio: 2),
                    (min-width: 992px) and (min-resolution: 192dpi) {
                        #<?php echo $id; ?> .item-<?php echo $index; ?>:before {
                            background-image: url("<?php echo image_sizer($image, 1200, 900, 'center', 'center'); ?>");
                        }
                    }

                    /* LG Breakpoint */
                    @media (min-width: 1200px) {
                        #<?php echo $id; ?> .item-<?php echo $index; ?>:before {
                            background-image: url("<?php echo image_sizer($image, 750, 563, 'center', 'center'); ?>");
                        }
                    }

                    /* LG Breakpoint, retina */
                    @media (min-width: 1200px) and (-webkit-min-device-pixel-ratio: 2),
                    (min-width: 1200px) and (min-resolution: 192dpi) {
                        #<?php echo $id; ?> .item-<?php echo $index; ?>:before {
                            background-image: url("<?php echo image_sizer($image, 1500, 1125, 'center', 'center'); ?>");
                        }
                    }

                    /* XL Breakpoint */
                    @media (min-width: 1500px) {
                        #<?php echo $id; ?> .item-<?php echo $index; ?>:before {
                            background-image: url("<?php echo image_sizer($image, 961, 720, 'center', 'center'); ?>");
                        }
                    }

                    /* XL Breakpoint, retina */
                    @media (min-width: 1500px) and (-webkit-min-device-pixel-ratio: 2),
                    (min-width: 1500px) and (min-resolution: 192dpi) {
                        #<?php echo $id; ?> .item-<?php echo $index; ?>:before {
                            background-image: url("<?php echo image_sizer($image, 1921, 1441, 'center', 'center'); ?>");
                        }
                    }

                    /* XXL Breakpoint */
                    @media (min-width: 1921px) {
                        #<?php echo $id; ?> .item-<?php echo $index; ?>:before {
                            background-image: url("<?php echo image_sizer($image, 1280, 960, 'center', 'center'); ?>");
                        }
                    }

                    /* XXL Breakpoint, retina */
                    @media (min-width: 1921px) and (-webkit-min-device-pixel-ratio: 2),
                    (min-width: 1921px) and (min-resolution: 192dpi) {
                        #<?php echo $id; ?> .item-<?php echo $index; ?>:before {
                            background-image: url("<?php echo image_sizer($image, 2560, 1920, 'center', 'center'); ?>");
                        }
                    }
                </style>
                <?php } elseif ( function_exists( 'fly_add_image_size' ) ) { // Background styles for one tile in one row with Fly plugin ?>
                <style>
                    #<?php echo $id; ?> .item-<?php echo $index; ?>:before {
                        background-image: url("<?php echo image_sizer($image, 576, 432, 'center', 'center'); ?>");
                    }

                    /* XS Breakpoint, retina */
                    @media (-webkit-min-device-pixel-ratio: 2),
                    (min-resolution: 192dpi) {
                        #<?php echo $id; ?> .item-<?php echo $index; ?>:before {
                            background-image: url("<?php echo image_sizer($image, 1152, 864, 'center', 'center'); ?>");
                        }
                    }

                    /* XS Breakpoint */
                    @media (min-width: 576px) {
                        #<?php echo $id; ?> .item-<?php echo $index; ?>:before {
                            background-image: url("<?php echo image_sizer($image, 768, 576, 'center', 'center'); ?>");
                        }
                    }

                    /* XS Breakpoint, retina */
                    @media (min-width: 576px) and (-webkit-min-device-pixel-ratio: 2),
                    (min-width: 576px) and (min-resolution: 192dpi) {
                        #<?php echo $id; ?> .item-<?php echo $index; ?>:before {
                            background-image: url("<?php echo image_sizer($image, 1536, 1152, 'center', 'center'); ?>");
                        }
                    }

                    /* SM Breakpoint */
                    @media (min-width: 768px) {
                        #<?php echo $id; ?> .item-<?php echo $index; ?>:before {
                            background-image: url("<?php echo image_sizer($image, 992, 744, 'center', 'center'); ?>");
                        }
                    }

                    /* SM Breakpoint, retina */
                    @media (min-width: 768px) and (-webkit-min-device-pixel-ratio: 2),
                    (min-width: 768px) and (min-resolution: 192dpi) {
                        #<?php echo $id; ?> .item-<?php echo $index; ?>:before {
                            background-image: url("<?php echo image_sizer($image, 1984, 1488, 'center', 'center'); ?>");
                        }
                    }

                    /* MD Breakpoint */
                    @media (min-width: 992px) {
                        #<?php echo $id; ?> .item-<?php echo $index; ?>:before {
                            background-image: url("<?php echo image_sizer($image, 1200, 900, 'center', 'center'); ?>");
                        }
                    }

                    /* MD Breakpoint, retina */
                    @media (min-width: 992px) and (-webkit-min-device-pixel-ratio: 2),
                    (min-width: 992px) and (min-resolution: 192dpi) {
                        #<?php echo $id; ?> .item-<?php echo $index; ?>:before {
                            background-image: url("<?php echo image_sizer($image, 2400, 1800, 'center', 'center'); ?>");
                        }
                    }

                    /* LG Breakpoint */
                    @media (min-width: 1200px) {
                        #<?php echo $id; ?> .item-<?php echo $index; ?>:before {
                            background-image: url("<?php echo image_sizer($image, 1500, 1125, 'center', 'center'); ?>");
                        }
                    }

                    /* LG Breakpoint, retina */
                    @media (min-width: 1200px) and (-webkit-min-device-pixel-ratio: 2),
                    (min-width: 1200px) and (min-resolution: 192dpi) {
                        #<?php echo $id; ?> .item-<?php echo $index; ?>:before {
                            background-image: url("<?php echo image_sizer($image, 3000, 2250, 'center', 'center'); ?>");
                        }
                    }

                    /* XL Breakpoint */
                    @media (min-width: 1500px) {
                        #<?php echo $id; ?> .item-<?php echo $index; ?>:before {
                            background-image: url("<?php echo image_sizer($image, 1921, 1441, 'center', 'center'); ?>");
                        }
                    }

                    /* XL Breakpoint, retina */
                    @media (min-width: 1500px) and (-webkit-min-device-pixel-ratio: 2),
                    (min-width: 1500px) and (min-resolution: 192dpi) {
                        #<?php echo $id; ?> .item-<?php echo $index; ?>:before {
                            background-image: url("<?php echo image_sizer($image, 3842, 2882, 'center', 'center'); ?>");
                        }
                    }

                    /* XXL Breakpoint */
                    @media (min-width: 1921px) {
                        #<?php echo $id; ?> .item-<?php echo $index; ?>:before {
                            background-image: url("<?php echo image_sizer($image, 2560, 1920, 'center', 'center'); ?>");
                        }
                    }

                    /* XXL Breakpoint, retina */
                    @media (min-width: 1921px) and (-webkit-min-device-pixel-ratio: 2),
                    (min-width: 1921px) and (min-resolution: 192dpi) {
                        #<?php echo $id; ?> .item-<?php echo $index; ?>:before {
                            background-image: url("<?php echo image_sizer($image, 5120, 3840, 'center', 'center'); ?>");
                        }
                    }
                </style>
                <?php } else { // Background styles for no Fly plugin ?>
                <style>
                    #<?php echo $id; ?> .item-<?php echo $index; ?>:before {
                            background-image: url("<?php echo wp_get_attachment_image_url( $image, 'aspect-4-3' ); ?>");
                    }
                </style>
                <?php } //endif ?>
                <div class="text-container">
                    <h2><?php echo $heading; ?></h2>
                    <p><?php echo $body; ?></p>
                    <a href="<?php echo $button_url; ?>" aria-label="<?php echo $button_desc; ?>" class="btn"<?php echo $button_target ? ' target="'. $button_target .'"' : ''; ?>><?php echo $button_text; ?></a>
                </div>
            </section>
<?php
        $index++;
    }
?>
        </div>
    </div>
</div>
<?php endif;