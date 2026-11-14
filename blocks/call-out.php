<?php

/** @var int|false $i Module index, set by uamswp_module() when this template is included as a module; an ACF block always has $block and an id, so the index is not read. */
/*
 *
 * UAMS Call-Out Block
 *
 */

// Editor placeholder for a newly inserted, still-empty block (ACF Blocks V3).
if (uamswp_block_placeholder($block ?? null, $is_preview ?? false)) {
    return;
}

$blockData = isset($block) ? uamswp_field_array($block) : [];
$moduleData = isset($module) ? uamswp_field_array($module) : [];

// Create id attribute allowing for custom "anchor" value.
if (empty($id)) {
    $id = '';
}
if (empty($id) && isset($block)) {
    $id = $blockData['id'] ?? null;
}
if (empty($id)) {
    $id = ! empty($moduleData['anchor_id']) ? sanitize_title_with_dashes(uamswp_field_text($moduleData['anchor_id'])) : 'module-'.((int) $i + 1);
}

$id = 'call-out-'.uamswp_field_text($id);
if (! empty($blockData['anchor'])) {
    $id = uamswp_field_text($blockData['anchor']);
}

$className = '';
if (! empty($blockData['className'])) {
    // @pest-mutate-ignore: ConcatEqualToEqual
    $className .= ' '.uamswp_field_text($blockData['className']);
}
if (! empty($blockData['align'])) {
    $className .= ' align'.uamswp_field_text($blockData['align']);
}

// Load values.
if (empty($heading)) {
    $heading = get_field('call_out_heading');
}
if (empty($body)) {
    $body = get_field('call_out_body');
}
if (empty($use_image)) {
    $use_image = get_field('call_out_use_image');
}
if (empty($image)) {
    $image = get_field('call_out_image');
}
if (empty($background_color)) {
    $background_color = get_field('call_out_background_color');
}
if (empty($geo)) {
    $geo = get_field('call_out_geo');
}
if (empty($geo_region)) {
    $geo_region = get_field('call_out_geo_region');
}

// echo '<!-- '; print_r($geo); echo ' -->';
// echo '<!-- ' . do_shortcode( '[geot_debug]' ) . ' -->';
// GEO Logic
$geo_display = false;
$geo_regions = uamswp_field_array($geo_region);
if (! isset($geo) || $geo_regions === []) {
    $geo_display = true;
} else {
    if ($geo == 'include') {
        if (is_in_region($geo_regions)) {
            $geo_display = true;
        }
    } elseif ($geo == 'exclude') {
        if (is_not_in_region($geo_regions)) {
            $geo_display = true;
        }
    }
}
if (! empty($is_preview) && ! empty($geo) && $geo_regions !== []) {
    $geo_display = true;
    echo ucwords(uamswp_field_text($geo)).' region(s): '.implode(', ', array_map(uamswp_field_text(...), $geo_regions)).'<hr>';
}
if ($geo_display) {
    ?>
    <section class="uams-module extra-padding call-out<?php echo esc_attr($className); ?> <?php echo esc_attr(uamswp_field_text($background_color)); ?><?php echo $use_image ? ' bg-image' : ''; ?>" id="<?php echo esc_attr($id); ?>" aria-label="<?php echo esc_attr(uamswp_field_text($heading)); ?>">
        <?php if ($use_image && function_exists('bis_get_attachment_image')) { ?>
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
        <?php } elseif ($use_image) { ?>
        <style>
            #<?php echo esc_attr($id); ?>:before {
                background-image: url("<?php echo wp_get_attachment_url($image); ?>");
            }
        </style>
        <?php } // endif?>
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="text-container">
                        <div class="call-out-heading">
                            <h2><?php echo esc_html(uamswp_field_text($heading)); ?></h2>
                        </div>
                        <div class="call-out-body">
                            <p><?php echo wp_kses_post(uamswp_field_text($body)); ?></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
<?php }
