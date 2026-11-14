<?php

/** @var int|false $i Module index, set by uamswp_module() when this template is included as a module; an ACF block always has $block and an id, so the index is not read. */
/*
 *
 * UAMS Text & Image Overlay Block
 *
 */

// Editor placeholder for a newly inserted, still-empty block (ACF Blocks V3).
if (uamswp_block_placeholder($block ?? null, $is_preview ?? false)) {
    return;
}

// Create id attribute allowing for custom "anchor" value.
$blockData = isset($block) ? uamswp_field_array($block) : [];
$moduleData = isset($module) ? uamswp_field_array($module) : [];
if (empty($id)) {
    $id = '';
}
if (empty($id) && isset($block)) {
    $id = $blockData['id'] ?? null;
}
if (empty($id)) {
    $id = ! empty($moduleData['anchor_id']) ? sanitize_title_with_dashes(uamswp_field_text($moduleData['anchor_id'])) : 'module-'.((int) $i + 1);
}

$id = 'text-image-overlay-'.uamswp_field_text($id);
if (! empty($blockData['anchor'])) {
    $id = $blockData['anchor'];
}
$id = sanitize_html_class(uamswp_field_text($id));

$className = '';
if (! empty($blockData['className'])) {
    // @pest-mutate-ignore: ConcatEqualToEqual
    $className .= ' '.uamswp_field_text($blockData['className']);
}
if (! empty($blockData['align'])) {
    $className .= ' align'.uamswp_field_text($blockData['align']);
}
if (empty($geo)) {
    $geo = get_field('overlay_geo');
}
if (empty($geo_region)) {
    $geo_region = get_field('overlay_geo_region');
}

if (empty($overlay_rows)) {
    $overlay_rows = get_field('overlay_section');
}
$overlay_rows = uamswp_field_array($overlay_rows);

// GEO Logic
$geo_region = uamswp_field_array($geo_region);
$geo_display = false;
if (! isset($geo) || $geo_region === []) {
    $geo_display = true;
} else {
    if ($geo == 'include') {
        if (is_in_region($geo_region)) {
            $geo_display = true;
        }
    } elseif ($geo == 'exclude') {
        if (is_not_in_region($geo_region)) {
            $geo_display = true;
        }
    }
}
if (! empty($is_preview) && ! empty($geo) && $geo_region !== []) {
    $geo_display = true;
    echo ucwords(uamswp_field_text($geo)).' region(s): '.implode(', ', array_map(uamswp_field_text(...), $geo_region)).'<hr>';
}
if ($geo_display) {

    if ($overlay_rows !== []) {
        $row_count = count($overlay_rows);

        ?>
<div class="uams-module no-padding text-image-overlay<?php echo $className; ?>" id="<?php echo $id; ?>">
    <div class="container-fluid">
        <div class="row">
<?php
            $index = 1;
        foreach ($overlay_rows as $overlay_row) {
            $overlay_row = uamswp_field_array($overlay_row);
            // Load values and adding defaults.
            $heading = $overlay_row['overlay_section_heading'] ?? null;
            $body = $overlay_row['overlay_section_body'] ?? null;
            $button_text = $overlay_row['overlay_section_button_text'] ?? null;
            $button_url = ''; // Define variable
            $button_target = ''; // Define variable
            $button_link = uamswp_field_array($overlay_row['overlay_section_button_url'] ?? null);
            if ($button_link !== []) {
                $button_url = $button_link['url'] ?? null;
                $button_target = $button_link['target'] ?? null;
            }
            $button_desc = $overlay_row['overlay_section_button_description'] ?? null;
            $background_color = $overlay_row['overlay_section_background_color'] ?? null;
            $image = $overlay_row['overlay_section_image'] ?? null;

            ?>
            <section class="col-12<?php echo $row_count > 1 ? ' col-sm-6' : ''; ?> item bg-image item-<?php echo $index; ?> <?php echo uamswp_field_text($background_color); ?>" aria-label="<?php echo esc_attr(uamswp_field_text($heading)); ?>">
                <?php if ($row_count > 1 && function_exists('bis_get_attachment_image')) { // Background styles for two tiles in one row with BIS plugin?>
                <style>
                    #<?php echo $id; ?> .item-<?php echo $index; ?>:before {
                        background-image: url("<?php echo image_sizer($image, 576, 432, 'center', 'center', 'aspect-4-3-small'); ?>");
                    }

                    /* XS Breakpoint */
                    @media (min-width: 576px) {
                        #<?php echo $id; ?> .item-<?php echo $index; ?>:before {
                            background-image: url("<?php echo image_sizer($image, 768, 576, 'center', 'center', 'aspect-4-3'); ?>");
                        }
                    }

                    /* SM Breakpoint */
                    @media (min-width: 768px) {
                        #<?php echo $id; ?> .item-<?php echo $index; ?>:before {
                            background-image: url("<?php echo image_sizer($image, 496, 372, 'center', 'center', 'aspect-4-3-small'); ?>");
                        }
                    }

                    /* MD Breakpoint */
                    @media (min-width: 992px) {
                        #<?php echo $id; ?> .item-<?php echo $index; ?>:before {
                            background-image: url("<?php echo image_sizer($image, 600, 450, 'center', 'center', 'aspect-4-3-samll'); ?>");
                        }
                    }

                    /* LG Breakpoint */
                    @media (min-width: 1200px) {
                        #<?php echo $id; ?> .item-<?php echo $index; ?>:before {
                            background-image: url("<?php echo image_sizer($image, 750, 563, 'center', 'center', 'aspect-4-3-samll'); ?>");
                        }
                    }

                    /* XL Breakpoint */
                    @media (min-width: 1500px) {
                        #<?php echo $id; ?> .item-<?php echo $index; ?>:before {
                            background-image: url("<?php echo image_sizer($image, 961, 720, 'center', 'center', 'aspect-4-3'); ?>");
                        }
                    }

                    /* XXL Breakpoint */
                    @media (min-width: 1921px) {
                        #<?php echo $id; ?> .item-<?php echo $index; ?>:before {
                            background-image: url("<?php echo image_sizer($image, 1280, 960, 'center', 'center', 'aspect-4-3'); ?>");
                        }
                    }
                </style>
                <?php } elseif (function_exists('bis_get_attachment_image')) { // Background styles for one tile in one row with BIS plugin?>
                <style>
                    #<?php echo $id; ?> .item-<?php echo $index; ?>:before {
                        background-image: url("<?php echo image_sizer($image, 576, 432, 'center', 'center', 'aspect-4-3-small'); ?>");
                    }

                    /* XS Breakpoint */
                    @media (min-width: 576px) {
                        #<?php echo $id; ?> .item-<?php echo $index; ?>:before {
                            background-image: url("<?php echo image_sizer($image, 768, 576, 'center', 'center', 'aspect-4-3-small'); ?>");
                        }
                    }

                    /* SM Breakpoint */
                    @media (min-width: 768px) {
                        #<?php echo $id; ?> .item-<?php echo $index; ?>:before {
                            background-image: url("<?php echo image_sizer($image, 992, 744, 'center', 'center', 'aspect-4-3'); ?>");
                        }
                    }

                    /* MD Breakpoint */
                    @media (min-width: 992px) {
                        #<?php echo $id; ?> .item-<?php echo $index; ?>:before {
                            background-image: url("<?php echo image_sizer($image, 1200, 900, 'center', 'center', 'aspect-4-3'); ?>");
                        }
                    }

                    /* LG Breakpoint */
                    @media (min-width: 1200px) {
                        #<?php echo $id; ?> .item-<?php echo $index; ?>:before {
                            background-image: url("<?php echo image_sizer($image, 1500, 1125, 'center', 'center', 'aspect-4-3'); ?>");
                        }
                    }

                    /* XL Breakpoint */
                    @media (min-width: 1500px) {
                        #<?php echo $id; ?> .item-<?php echo $index; ?>:before {
                            background-image: url("<?php echo image_sizer($image, 1921, 1441, 'center', 'center', 'aspect-4-3'); ?>");
                        }
                    }

                    /* XXL Breakpoint */
                    @media (min-width: 1921px) {
                        #<?php echo $id; ?> .item-<?php echo $index; ?>:before {
                            background-image: url("<?php echo image_sizer($image, 2560, 1920, 'center', 'center', 'aspect-4-3'); ?>");
                        }
                    }
                </style>
                <?php } else { // Background styles for no BIS plugin?>
                <style>
                    #<?php echo $id; ?> .item-<?php echo $index; ?>:before {
                            background-image: url("<?php echo wp_get_attachment_image_url($image, 'aspect-4-3'); ?>");
                    }
                </style>
                <?php } // endif?>
                <div class="text-container">
                    <h2><?php echo esc_html(uamswp_field_text($heading)); ?></h2>
                    <p><?php echo wp_kses_post(uamswp_field_text($body)); ?></p>
                    <a href="<?php echo esc_url(uamswp_field_text($button_url)); ?>" aria-label="<?php echo esc_attr(uamswp_field_text($button_desc)); ?>" class="btn btn-white"<?php echo $button_target ? ' target="'.esc_attr(uamswp_field_text($button_target)).'"' : ''; ?> data-itemtitle="<?php echo esc_attr(uamswp_field_text($heading)); ?>"><?php echo esc_html(uamswp_field_text($button_text)); ?></a>
                </div>
            </section>
<?php
                    $index++;
        }
        ?>
        </div>
    </div>
</div>
<?php }
    }
