<?php
/*
 *
 * UAMS Call-Out Block
 * 
 */

// Create id attribute allowing for custom "anchor" value.
if ( empty( $id ) )
    $id = $block['id'];

$id = 'call-out-' . $id;

$className = '';
if( !empty($block['className']) ) {
    $className .= ' ' . $block['className'];
}
if( !empty($block['align']) ) {
    $className .= ' align' . $block['align'];
}

// Load values.
if ( empty($heading) ) 
    $heading = get_field('call_out_heading');
if ( empty($body) ) 
    $body = get_field('call_out_body');
if ( empty($use_image) ) 
    $use_image = get_field('call_out_use_image');
if ( empty($image) ) 
    $image = get_field('call_out_image');
if ( empty($background_color) ) 
    $background_color = get_field('call_out_background_color');

?>
<section class="uams-module extra-padding call-out<?php echo $className; ?> <?php echo $background_color; ?><?php echo $use_image ? ' bg-image' : ''; ?>" id="<?php echo $id; ?>" aria-label="<?php echo $heading; ?>">
    <?php if ( $use_image && function_exists( 'fly_add_image_size' ) ) { ?>
    <style>
        #<?php echo $id; ?>:before {
            background-image: url("<?php echo image_sizer($image, 576, 216, 'center', 'center'); ?>");
        }

        /* XXS Breakpoint, retina */
        @media (-webkit-min-device-pixel-ratio: 2),
        (min-resolution: 192dpi) {
            #<?php echo $id; ?>:before {
                background-image: url("<?php echo image_sizer($image, 1152, 432, 'center', 'center'); ?>");
            }
        }

        /* XS Breakpoint */
        @media (min-width: 576px) {
            #<?php echo $id; ?>:before {
                background-image: url("<?php echo image_sizer($image, 768, 288, 'center', 'center'); ?>");
            }
        }

        /* XS Breakpoint, retina */
        @media (min-width: 576px) and (-webkit-min-device-pixel-ratio: 2),
        (min-width: 576px) and (min-resolution: 192dpi) {
            #<?php echo $id; ?>:before {
                background-image: url("<?php echo image_sizer($image, 1536, 576, 'center', 'center'); ?>");
            }
        }

        /* SM Breakpoint */
        @media (min-width: 768px) {
            #<?php echo $id; ?>:before {
                background-image: url("<?php echo image_sizer($image, 992, 372, 'center', 'center'); ?>");
            }
        }

        /* SM Breakpoint, retina */
        @media (min-width: 768px) and (-webkit-min-device-pixel-ratio: 2),
        (min-width: 768px) and (min-resolution: 192dpi) {
            #<?php echo $id; ?>:before {
                background-image: url("<?php echo image_sizer($image, 1984, 744, 'center', 'center'); ?>");
            }
        }

        /* MD Breakpoint */
        @media (min-width: 992px) {
            #<?php echo $id; ?>:before {
                background-image: url("<?php echo image_sizer($image, 1200, 450, 'center', 'center'); ?>");
            }
        }

        /* MD Breakpoint, retina */
        @media (min-width: 992px) and (-webkit-min-device-pixel-ratio: 2),
        (min-width: 992px) and (min-resolution: 192dpi) {
            #<?php echo $id; ?>:before {
                background-image: url("<?php echo image_sizer($image, 2400, 900, 'center', 'center'); ?>");
            }
        }

        /* LG Breakpoint */
        @media (min-width: 1200px) {
            #<?php echo $id; ?>:before {
                background-image: url("<?php echo image_sizer($image, 1500, 563, 'center', 'center'); ?>");
            }
        }

        /* LG Breakpoint, retina */
        @media (min-width: 1200px) and (-webkit-min-device-pixel-ratio: 2),
        (min-width: 1200px) and (min-resolution: 192dpi) {
            #<?php echo $id; ?>:before {
                background-image: url("<?php echo image_sizer($image, 3000, 1125, 'center', 'center'); ?>");
            }
        }

        /* XL Breakpoint */
        @media (min-width: 1500px) {
            #<?php echo $id; ?>:before {
                background-image: url("<?php echo image_sizer($image, 1921, 720, 'center', 'center'); ?>");
            }
        }

        /* XL Breakpoint, retina */
        @media (min-width: 1500px) and (-webkit-min-device-pixel-ratio: 2),
        (min-width: 1500px) and (min-resolution: 192dpi) {
            #<?php echo $id; ?>:before {
                background-image: url("<?php echo image_sizer($image, 3842, 1441, 'center', 'center'); ?>");
            }
        }

        /* XXL Breakpoint */
        @media (min-width: 1921px) {
            #<?php echo $id; ?>:before {
                background-image: url("<?php echo image_sizer($image, 2560, 960, 'center', 'center'); ?>");
            }
        }

        /* XXL Breakpoint, retina */
        @media (min-width: 1921px) and (-webkit-min-device-pixel-ratio: 2),
        (min-width: 1921px) and (min-resolution: 192dpi) {
            #<?php echo $id; ?>:before {
                background-image: url("<?php echo image_sizer($image, 5120, 1920, 'center', 'center'); ?>");
            }
        }
    </style>
    <?php } elseif ( $use_image ) { ?>
    <style>
        #<?php echo $id; ?>:before {
            background-image: url("<?php echo wp_get_attachment_url( $image, 'aspect-8-3' ); ?>");
        }
    </style>
    <?php } //endif ?>
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="text-container">
                    <div class="call-out-heading">
                        <h2><?php echo $heading; ?></h2>
                    </div>
                    <div class="call-out-body">
                        <p><?php echo $body; ?></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>