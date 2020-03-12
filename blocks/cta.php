<?php
/*
 *
 * UAMS CTA Bar Block
 * 
 */

// Create id attribute allowing for custom "anchor" value.
$id = '';
$i = 0;
if ( empty( $id ) && isset($block) ) {
    $id = $block['id'];
} 
if ( empty ($id) ) {
    $id = !empty( $module['anchor_id'] ) ? sanitize_title_with_dashes( $module['anchor_id'] ) : 'module-' . ( $i + 1 );
}
    
$id = 'cta-bar-' . $id;

$className = '';
if( !empty($block['className']) ) {
    $className .= ' ' . $block['className'];
}
if( !empty($block['align']) ) {
    $className .= ' align' . $block['align'];
}

// Load values and assing defaults.
// if empty - allow values to be set for widgets
if ( empty($heading) ) 
    $heading = get_field('cta_bar_heading');
if ( empty($body) ) 
    $body = get_field('cta_bar_body');
if ( empty($button_text) ) 
    $button_text = get_field('cta_bar_button_text');
if ( empty($button_url) ) 
    $button_url = get_field('cta_bar_button_url');
if ( empty($button_target) ) 
    $button_target = get_field('cta_bar_button_url')['target'];
if ( empty($button_desc) ) 
    $button_desc = get_field('cta_bar_button_description');
if ( empty($layout) ) 
    $layout = get_field('cta_bar_layout');
if ( empty($use_image) ) 
    $use_image = get_field('cta_bar_use_image');
if ( empty($image) ) 
    $image = get_field('cta_bar_image');
if ( empty($background_color) ) 
    $background_color = get_field('cta_bar_background_color');
if ( $background_color == 'bg-white' || $background_color == 'bg-gray' ) {
    $btn_color = 'primary';
} else {
    $btn_color = 'white';
}

?>
<section class="uams-module cta-bar <?php echo $className; ?> <?php echo $layout; ?> <?php echo $background_color; ?><?php echo $use_image ? ' bg-image' : ''; ?><?php echo $button_url ? '' : ' no-link'; ?>" id="<?php echo $id; ?>" aria-label="<?php echo $heading; ?>">
<?php if ( $use_image && function_exists( 'fly_add_image_size' ) ) { ?>
    <style>
        #<?php echo $id; ?>:before {
            background-image: url("<?php echo image_sizer($image, 576, 288, 'center', 'center'); ?>");
        }

        /* XXS Breakpoint, retina */
        @media (-webkit-min-device-pixel-ratio: 2),
        (min-resolution: 192dpi) {
            #<?php echo $id; ?>:before {
                background-image: url("<?php echo image_sizer($image, 1152, 576, 'center', 'center'); ?>");
            }
        }

        /* XS Breakpoint */
        @media (min-width: 576px) {
            #<?php echo $id; ?>:before {
                background-image: url("<?php echo image_sizer($image, 768, 384, 'center', 'center'); ?>");
            }
        }

        /* XS Breakpoint, retina */
        @media (min-width: 576px) and (-webkit-min-device-pixel-ratio: 2),
        (min-width: 576px) and (min-resolution: 192dpi) {
            #<?php echo $id; ?>:before {
                background-image: url("<?php echo image_sizer($image, 1536, 768, 'center', 'center'); ?>");
            }
        }

        /* SM Breakpoint */
        @media (min-width: 768px) {
            #<?php echo $id; ?>:before {
                background-image: url("<?php echo image_sizer($image, 992, 496, 'center', 'center'); ?>");
            }
        }

        /* SM Breakpoint, retina */
        @media (min-width: 768px) and (-webkit-min-device-pixel-ratio: 2),
        (min-width: 768px) and (min-resolution: 192dpi) {
            #<?php echo $id; ?>:before {
                background-image: url("<?php echo image_sizer($image, 1984, 992, 'center', 'center'); ?>");
            }
        }

        /* MD Breakpoint */
        @media (min-width: 992px) {
            #<?php echo $id; ?>:before {
                background-image: url("<?php echo image_sizer($image, 1200, 600, 'center', 'center'); ?>");
            }
        }

        /* MD Breakpoint, retina */
        @media (min-width: 992px) and (-webkit-min-device-pixel-ratio: 2),
        (min-width: 992px) and (min-resolution: 192dpi) {
            #<?php echo $id; ?>:before {
                background-image: url("<?php echo image_sizer($image, 2400, 1200, 'center', 'center'); ?>");
            }
        }

        /* LG Breakpoint */
        @media (min-width: 1200px) {
            #<?php echo $id; ?>:before {
                background-image: url("<?php echo image_sizer($image, 1500, 750, 'center', 'center'); ?>");
            }
        }

        /* LG Breakpoint, retina */
        @media (min-width: 1200px) and (-webkit-min-device-pixel-ratio: 2),
        (min-width: 1200px) and (min-resolution: 192dpi) {
            #<?php echo $id; ?>:before {
                background-image: url("<?php echo image_sizer($image, 3000, 1500, 'center', 'center'); ?>");
            }
        }

        /* XL Breakpoint */
        @media (min-width: 1500px) {
            #<?php echo $id; ?>:before {
                background-image: url("<?php echo image_sizer($image, 1921, 961, 'center', 'center'); ?>");
            }
        }

        /* XL Breakpoint, retina */
        @media (min-width: 1500px) and (-webkit-min-device-pixel-ratio: 2),
        (min-width: 1500px) and (min-resolution: 192dpi) {
            #<?php echo $id; ?>:before {
                background-image: url("<?php echo image_sizer($image, 3842, 1921, 'center', 'center'); ?>");
            }
        }

        /* XXL Breakpoint */
        @media (min-width: 1921px) {
            #<?php echo $id; ?>:before {
                background-image: url("<?php echo image_sizer($image, 2560, 1280, 'center', 'center'); ?>");
            }
        }

        /* XXL Breakpoint, retina */
        @media (min-width: 1921px) and (-webkit-min-device-pixel-ratio: 2),
        (min-width: 1921px) and (min-resolution: 192dpi) {
            #<?php echo $id; ?>:before {
                background-image: url("<?php echo image_sizer($image, 5120, 2560, 'center', 'center'); ?>");
            }
        }
    </style>
    <?php } elseif ( $use_image ) { ?>
    <style>
        #<?php echo $id; ?>:before {
            background-image: url("<?php echo wp_get_attachment_url( $image, 'aspect-2-1' ); ?>");
        }
    </style>
    <?php } //endif ?>
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
                            <a href="' . $button_url['url'] . '" aria-label="' . $button_desc . '" class=" btn btn-' . $btn_color . '"' . ( $button_target ? ' target="'. $button_target . '"' : '' ) . ' data-moduletitle="' . $heading . '">' . $button_text . '</a>
                        </div>'
                        : ''; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>