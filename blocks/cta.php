<?php
/*
 *
 * UAMS CTA Bar Block
 * 
 */

// Create id attribute allowing for custom "anchor" value.
$id = 'cta-bar-' . $block['id'];

// Load values and assing defaults.
$heading = get_field('cta-bar_heading');
$body = get_field('cta-bar_body');
$button_text = get_field('cta-bar_button-text');
$button_url = get_field('cta-bar_button-url');
$button_target = get_field('cta-bar_button-target');
$button_desc = get_field('cta-bar_button-description');
$layout = get_field('cta-bar_layout');
$use_image = get_field('cta-bar_use-image');
$image = get_field('cta-bar_image');
$background_color = get_field('cta-bar_background-color');

?>
<section class="uams-module cta-bar <?php echo $layout; ?> <?php echo $background_color; ?><?php echo $use_image ? ' bg-image' : ''; ?>" id="<?php echo $id; ?>">
    <?php echo $use_image ?
    '<style>
        #' . $id . ':before {
            background-image: url("' . wp_get_attachment_image_url( $image, 'full' ) . '");
        }

        /* XXS Breakpoint, retina */
        @media (-webkit-min-device-pixel-ratio: 2),
        (min-resolution: 192dpi) {
            #' . $id . ':before {
                background-image: url("' . wp_get_attachment_image_url( $image, 'full' ) . '");
            }
        }

        /* XS Breakpoint */
        @media (min-width: 576px) {
            #' . $id . ':before {
                background-image: url("' . wp_get_attachment_image_url( $image, 'full' ) . '");
            }
        }

        /* XS Breakpoint, retina */
        @media (min-width: 576px) and (-webkit-min-device-pixel-ratio: 2),
        (min-width: 576px) and (min-resolution: 192dpi) {
            #' . $id . ':before {
                background-image: url("' . wp_get_attachment_image_url( $image, 'full' ) . '");
            }
        }

        /* SM Breakpoint */
        @media (min-width: 768px) {
            #' . $id . ':before {
                background-image: url("' . wp_get_attachment_image_url( $image, 'full' ) . '");
            }
        }

        /* SM Breakpoint, retina */
        @media (min-width: 768px) and (-webkit-min-device-pixel-ratio: 2),
        (min-width: 768px) and (min-resolution: 192dpi) {
            #' . $id . ':before {
                background-image: url("' . wp_get_attachment_image_url( $image, 'full' ) . '");
            }
        }

        /* MD Breakpoint */
        @media (min-width: 992px) {
            #' . $id . ':before {
                background-image: url("' . wp_get_attachment_image_url( $image, 'full' ) . '");
            }
        }

        /* MD Breakpoint, retina */
        @media (min-width: 992px) and (-webkit-min-device-pixel-ratio: 2),
        (min-width: 992px) and (min-resolution: 192dpi) {
            #' . $id . ':before {
                background-image: url("' . wp_get_attachment_image_url( $image, 'full' ) . '");
            }
        }

        /* LG Breakpoint */
        @media (min-width: 1200px) {
            #' . $id . ':before {
                background-image: url("' . wp_get_attachment_image_url( $image, 'full' ) . '");
            }
        }

        /* LG Breakpoint, retina */
        @media (min-width: 1200px) and (-webkit-min-device-pixel-ratio: 2),
        (min-width: 1200px) and (min-resolution: 192dpi) {
            #' . $id . ':before {
                background-image: url("' . wp_get_attachment_image_url( $image, 'full' ) . '");
            }
        }

        /* XL Breakpoint */
        @media (min-width: 1500px) {
            #' . $id . ':before {
                background-image: url("' . wp_get_attachment_image_url( $image, 'full' ) . '");
            }
        }

        /* XL Breakpoint, retina */
        @media (min-width: 1500px) and (-webkit-min-device-pixel-ratio: 2),
        (min-width: 1500px) and (min-resolution: 192dpi) {
            #' . $id . ':before {
                background-image: url("' . wp_get_attachment_image_url( $image, 'full' ) . '");
            }
        }

        /* XXL Breakpoint */
        @media (min-width: 1500px) {
            #' . $id . ':before {
                background-image: url("' . wp_get_attachment_image_url( $image, 'full' ) . '");
            }
        }

        /* XXL Breakpoint, retina */
        @media (min-width: 1500px) and (-webkit-min-device-pixel-ratio: 2),
        (min-width: 1500px) and (min-resolution: 192dpi) {
            #' . $id . ':before {
                background-image: url("' . wp_get_attachment_image_url( $image, 'full' ) . '");
            }
        }
    </style>'
    : ''; ?>
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="inner-container">
                    <div class="cta-heading">
                        <h2><?php echo $heading; ?></h2>
                    </div>
                    <div class="cta-body">
                        <div class="text-container">
                            <?php echo $body; ?>
                        </div>
                        <?php echo $button_text ?
                        '<div class="btn-container">
                            <a href="' . $button_url . '" aria-label="' . $button_desc . '" class="btn"' . ( $button_target ? ' target="_blank"' : '' ) . '>' . $button_text . '</a>
                        </div>'
                        : ''; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>