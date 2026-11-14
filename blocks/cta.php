<?php

/** @var int|false $i Module index, set by uamswp_module() when this template is included as a module; an ACF block always has $block and an id, so the index is not read. */
/*
 *
 * UAMS CTA Bar Block
 *
 */

// Editor placeholder for a newly inserted, still-empty block (ACF Blocks V3).
if (uamswp_block_placeholder($block ?? null, $is_preview ?? false)) {
    return;
}

$blockData = isset($block) ? uamswp_field_array($block) : [];

// Create id attribute allowing for custom "anchor" value.
if (empty($id)) {
    $id = '';
}
$id = $id ? ((int) uamswp_field_text($id) + 1) : '';
$i = 0;
if (empty($id) && isset($block)) {
    $id = $blockData['id'] ?? null;
}
if (! empty($id)) {
    $id = 'module-'.uamswp_field_text($id);
} else {
    $id = $i + 1;
}
// if ( empty ($id) ) {
//     $id = !empty( $module['anchor_id'] ) ? sanitize_title_with_dashes( $module['anchor_id'] ) : 'module-' . ( $i + 1 );
// }

$id = 'cta-bar-'.uamswp_field_text($id);
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

// Load values and assessing defaults.
// if empty - allow values to be set for widgets
if (empty($heading)) {
    $heading = get_field('cta_bar_heading');
}
if (empty($body)) {
    $body = get_field('cta_bar_body');
}
if (empty($action_type)) {
    $action_type = get_field('cta_bar_action_type');
}
if (empty($button_text)) {
    $button_text = get_field('cta_bar_button_text');
}
if (empty($button_url)) {
    $button_url = get_field('cta_bar_button_url');
}
$button_target ??= '';
if ($button_url && empty($button_target)) {
    $button_field = uamswp_field_array(get_field('cta_bar_button_url'));
    if ($button_field !== []) {
        $button_target = uamswp_field_text($button_field['target'] ?? null);
    }
}
if (empty($button_desc)) {
    $button_desc = get_field('cta_bar_button_description');
}
if (empty($action_type) && $button_text) { // If still empty (meaning page hasn't been updated since code changed)
    $action_type = 'url';
}
if (empty($phone_prepend)) {
    $phone_prepend = uamswp_field_text(get_field('cta_bar_phone_prepend')) ?: 'Call';
}
if (empty($phone)) {
    $phone = get_field('cta_bar_phone');
}
if (
    empty($action_type) || // If still empty (meaning page hasn't been updated since code changed)
    (
        $action_type == 'url' &&
        (! $button_text || ! $button_url || ! $button_desc) // required fields aren't populated
    ) ||
    (
        $action_type == 'phone' &&
        (! $phone_prepend || ! $phone) // required fields aren't populated
    )
) {
    $action_type = 'none';
}
if (empty($layout)) {
    $layout = get_field('cta_bar_layout');
}
if (empty($size)) {
    $size = get_field('cta_bar_size');
}
if (empty($use_image)) {
    $use_image = get_field('cta_bar_use_image');
}
if (empty($image)) {
    $image = get_field('cta_bar_image');
}
if (empty($background_color)) {
    $background_color = get_field('cta_bar_background_color');
}
$heading = uamswp_field_text($heading);
$body = uamswp_field_text($body);
$button_text = uamswp_field_text($button_text);
$button_desc = uamswp_field_text($button_desc);
$phone_prepend = uamswp_field_text($phone_prepend);
$phone = uamswp_field_text($phone);
$button_target = uamswp_field_text($button_target);
$layout = uamswp_field_text($layout);
$background_color = uamswp_field_text($background_color);
$button_link = uamswp_field_array($button_url);
if (in_array($background_color, ['bg-white', 'bg-gray', 'bg-auto'], true)) {
    $btn_color = 'primary';
} else {
    $btn_color = 'white';
}
if (empty($geo)) {
    $geo = get_field('cta_bar_geo');
}
if (empty($geo_region)) {
    $geo_region = get_field('cta_bar_geo_region');
}

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
<section class="uams-module cta-bar <?php echo esc_attr($className); ?> <?php echo $layout; ?> <?php echo $background_color; ?><?php echo $use_image ? ' bg-image' : ''; ?><?php echo $size == 'small' ? ' cta-bar-sm' : ''; ?><?php echo $size == 'large' ? ' extra-padding cta-bar-lg' : ''; ?><?php echo $action_type == 'none' ? ' no-link' : ''; ?>" id="<?php echo esc_attr($id); ?>" aria-label="<?php echo esc_attr($heading); ?>">
<?php if ($use_image && function_exists('bis_get_attachment_image')) { ?>
    <style>
        #<?php echo sanitize_html_class($id); ?>:before {
            background-image: url("<?php echo image_sizer($image, 576, 288, 'center', 'center', 'aspect-2-1'); ?>");
        }

        /* XS Breakpoint */
        @media (min-width: 576px) {
            #<?php echo sanitize_html_class($id); ?>:before {
                background-image: url("<?php echo image_sizer($image, 768, 384, 'center', 'center', 'aspect-2-1'); ?>");
            }
        }

        /* SM Breakpoint */
        @media (min-width: 768px) {
            #<?php echo sanitize_html_class($id); ?>:before {
                background-image: url("<?php echo image_sizer($image, 992, 496, 'center', 'center', 'aspect-2-1'); ?>");
            }
        }

        /* MD Breakpoint */
        @media (min-width: 992px) {
            #<?php echo sanitize_html_class($id); ?>:before {
                background-image: url("<?php echo image_sizer($image, 1200, 600, 'center', 'center', 'aspect-2-1'); ?>");
            }
        }

        /* LG Breakpoint */
        @media (min-width: 1200px) {
            #<?php echo sanitize_html_class($id); ?>:before {
                background-image: url("<?php echo image_sizer($image, 1500, 750, 'center', 'center', 'aspect-2-1'); ?>");
            }
        }

        /* XL Breakpoint */
        @media (min-width: 1500px) {
            #<?php echo sanitize_html_class($id); ?>:before {
                background-image: url("<?php echo image_sizer($image, 1921, 961, 'center', 'center', 'aspect-2-1'); ?>");
            }
        }

        /* XXL Breakpoint */
        @media (min-width: 1921px) {
            #<?php echo sanitize_html_class($id); ?>:before {
                background-image: url("<?php echo image_sizer($image, 2560, 1280, 'center', 'center', 'aspect-2-1'); ?>");
            }
        }
    </style>
    <?php } elseif ($use_image) { ?>
    <style>
        #<?php echo sanitize_html_class($id); ?>:before {
            background-image: url("<?php echo wp_get_attachment_url($image); ?>");
        }
    </style>
    <?php } // endif?>
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="inner-container">
                    <div class="cta-heading">
                        <h2><?php echo esc_html($heading); ?></h2>
                    </div>
                    <div class="cta-body">
                        <div class="text-container">
                            <?php echo wp_kses_post($body); ?>
                        </div>
                        <?php echo $action_type == 'url' ?
                            '<div class="btn-container">
                            <a href="'.esc_url(uamswp_field_text($button_link['url'] ?? null)).'" aria-label="'.esc_attr($button_desc).'" class=" btn btn-'.$btn_color.($size == 'large' ? ' btn-lg' : '').'"'.($button_target ? ' target="'.esc_attr($button_target).'"' : '').' data-moduletitle="'.esc_attr($heading).'">'.esc_html($button_text).'</a>
                        </div>'
                            : ''; ?>
                        <?php echo $action_type == 'phone' ?
                            '<div class="btn-container">
                            <a href="'.esc_url('tel:'.$phone).'" data-moduletitle="'.esc_attr($heading).'">'.esc_html($phone_prepend).' <span class="no-break">'.esc_html($phone).'</span></a>
                        </div>'
                            : ''; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<?php }
