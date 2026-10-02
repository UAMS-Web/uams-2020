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
    <section class="uams-module extra-padding call-out<?php echo esc_attr($className); ?> <?php echo esc_attr($background_color); ?><?php echo $use_image ? ' bg-image' : ''; ?>" id="<?php echo esc_attr($id); ?>" aria-label="<?php echo esc_attr($heading); ?>">
        <?php if ( $use_image && function_exists( 'bis_get_attachment_image' ) ) { ?>
        <style>
            #<?php echo esc_attr($id); ?>:before {
                background-image: url("<?php echo image_sizer($image, 576, 216, 'center', 'center', 'aspect-8-3'); ?>");
            }

            /* XS Breakpoint */
            @media (min-width: 576px) {
                #<?php echo esc_attr($id); ?>:before {
                    background-image: url("<?php echo image_sizer($image, 768, 288, 'center', 'center', 'aspect-8-3'); ?>");
                }
            }
    
            /* SM Breakpoint */
            @media (min-width: 768px) {
                #<?php echo esc_attr($id); ?>:before {
                    background-image: url("<?php echo image_sizer($image, 992, 372, 'center', 'center', 'aspect-8-3'); ?>");
                }
            }
    
            /* MD Breakpoint */
            @media (min-width: 992px) {
                #<?php echo esc_attr($id); ?>:before {
                    background-image: url("<?php echo image_sizer($image, 1200, 450, 'center', 'center', 'aspect-8-3'); ?>");
                }
            }
    
            /* LG Breakpoint */
            @media (min-width: 1200px) {
                #<?php echo esc_attr($id); ?>:before {
                    background-image: url("<?php echo image_sizer($image, 1500, 563, 'center', 'center', 'aspect-8-3'); ?>");
                }
            }
    
            /* XL Breakpoint */
            @media (min-width: 1500px) {
                #<?php echo esc_attr($id); ?>:before {
                    background-image: url("<?php echo image_sizer($image, 1921, 720, 'center', 'center', 'aspect-8-3'); ?>");
                }
            }
    
            /* XXL Breakpoint */
            @media (min-width: 1921px) {
                #<?php echo esc_attr($id); ?>:before {
                    background-image: url("<?php echo image_sizer($image, 2560, 960, 'center', 'center', 'aspect-8-3'); ?>");
                }
            }
        </style>
        <?php } elseif ( $use_image ) { ?>
        <style>
            #<?php echo esc_attr($id); ?>:before {
                background-image: url("<?php echo wp_get_attachment_url( $image, 'aspect-8-3' ); ?>");
            }
        </style>
        <?php } //endif ?>
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="text-container">
                        <div class="call-out-heading">
                            <h2><?php echo esc_html($heading); ?></h2>
                        </div>
                        <div class="call-out-body">
                            <p><?php echo wp_kses_post($body); ?></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
<?php endif;