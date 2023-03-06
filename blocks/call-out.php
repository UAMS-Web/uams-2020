<?php
/*
 *
 * UAMS Call-Out Block
 *
 */

// Create id attribute allowing for custom "anchor" value.
if (empty( $id )) {
	$id = '';
}
if (empty( $i )) {
	$i = 0;
}
if ( empty( $id ) && isset($block) ) {
    $id = $block['id'];
}
if ( empty ($id) ) {
    $id = !empty( $module['anchor_id'] ) ? sanitize_title_with_dashes( $module['anchor_id'] ) : 'module-' . ( $i + 1 );
}

$id = 'call-out-' . $id;
if( !empty($block['anchor']) ) {
    $id = $block['anchor'];
}

$className = '';
if( !empty($block['className']) ) {
    $className .= ' ' . $block['className'];
}
if( !empty($block['align']) ) {
    $className .= ' align' . $block['align'];
}

// Load values.
if ( empty($heading) && get_field('call_out_heading') ) {
    $heading = get_field('call_out_heading');
} elseif ( empty($heading) && get_sub_field('call_out_heading') ) {
    $heading = get_sub_field('call_out_heading');
}
if ( empty($body) && get_field('call_out_body') ) {
    $body = get_field('call_out_body');
} elseif ( empty($body) && get_sub_field('call_out_body') ) {
    $body = get_sub_field('call_out_body');
}
if ( empty($use_image) && get_field('call_out_use_image') ) {
    $use_image = get_field('call_out_use_image');
} elseif ( empty($use_image) && get_sub_field('call_out_use_image') ) {
    $use_image = get_sub_field('call_out_use_image');
} else {
    $use_image = false;
}
if ( empty($image) && get_field('call_out_image') ) {
    $image = get_field('call_out_image');
} elseif ( empty($image) && get_sub_field('call_out_image') ) {
    $image = get_sub_field('call_out_image');
}
if ( empty($background_color) && get_field('call_out_background_color') ) {
    $background_color = get_field('call_out_background_color');
} elseif ( empty($background_color) && get_sub_field('call_out_background_color') ) {
    $background_color = get_sub_field('call_out_background_color');
}
if ( empty($geo) )
    $geo = get_field('call_out_geo');
if ( empty($geo_region) )
    $geo_region = get_field('call_out_geo_region');

// echo '<!-- '; print_r($geo); echo ' -->';
// echo '<!-- ' . do_shortcode( '[geot_debug]' ) . ' -->';
// GEO Logic
$geo_display = false;
if (!isset($geo) || empty($geo_region)){
    $geo_display = true;
} else {
    if( $geo == 'include' && !empty($geo_region) ) {
        if( is_in_region($geo_region) ){
            $geo_display = true;
        }
    } elseif( $geo == 'exclude' && !empty($geo_region) ) {
        if ( is_not_in_region($geo_region) ){
            $geo_display = true;
        }
    }
}
if (is_admin() && !empty($geo) && !empty($geo_region)) {
    $geo_display = true;
    echo ucwords($geo) . ' region(s): ' . implode(', ', $geo_region) . '<hr>';
}
if ($geo_display) : 
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
<?php endif;