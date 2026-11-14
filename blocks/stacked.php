<?php

/** @var int|false $i Module index, set by uamswp_module() when this template is included as a module; an ACF block always has $block and an id, so the index is not read. */
/*
 *
 * UAMS Stacked Image & Text Block
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

$id = 'stacked-image-text-'.uamswp_field_text($id);
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

// PHP variables
// @pest-mutate-ignore: EmptyStringToNotEmpty
$more_text = '';
// @pest-mutate-ignore: EmptyStringToNotEmpty
$more_button_text = '';
// @pest-mutate-ignore: EmptyStringToNotEmpty
$more_button_url = '';
$more_button_target = '';

// Load values.
if (empty($heading)) {
    $heading = get_field('stacked_heading');
}
if (empty($hide_heading)) {
    $hide_heading = get_field('stacked_hide_heading');
}
if (empty($description)) {
    $description = get_field('stacked_description');
}
if (empty($background_color)) {
    $background_color = get_field('stacked_background_color');
}
if (empty($more)) {
    $more = get_field('stacked_more');
}
if ($more) {
    $more_text = get_field('stacked_more_text');
    $more_button_text = get_field('stacked_more_button_text');
    $more_button_url = get_field('stacked_more_button_url');
    if (is_array($more_button_url)) {
        $more_button_target = uamswp_field_text($more_button_url['target'] ?? null);
    }
    if (empty($more_button_description)) {
        $more_button_description = get_field('stacked_more_button_description');
    }
    if (empty($more_button_color) && (in_array($background_color, ['bg-white', 'bg-gray', 'bg-auto'], true))) {
        $more_button_color = 'primary';
    } else {
        $more_button_color = 'white';
    }
}
if (empty($geo)) {
    $geo = get_field('stacked_geo');
}
if (empty($geo_region)) {
    $geo_region = get_field('stacked_geo_region');
}
if (empty($stacked_rows)) {
    $stacked_rows = get_field('stacked_section');
}

$heading = uamswp_field_text($heading);
$description = uamswp_field_text($description);
$background_color = uamswp_field_text($background_color);
$more_text = uamswp_field_text($more_text);
$more_button_text = uamswp_field_text($more_button_text);
$more_button_link = uamswp_field_array($more_button_url);
$more_button_target = uamswp_field_text($more_button_target);
$more_button_description = uamswp_field_text($more_button_description ?? null);
$stacked_rows = uamswp_field_array($stacked_rows);

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

    if ($stacked_rows !== []) {
        $row_count = count($stacked_rows); // Not user, but just in case

        ?>
<section class="uams-module stacked-image-text<?php echo esc_attr($className); ?> <?php echo esc_attr($background_color); ?>" id="<?php echo esc_attr($id); ?>" aria-label="<?php echo esc_attr($heading); ?>">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12<?php echo ($hide_heading && empty($description)) ? ' sr-only' : ''; ?>">
                <h2 class="module-title<?php echo ($hide_heading && $description) ? ' sr-only' : ''; ?>">
                    <span class="title"><?php echo esc_html($heading); ?></span>
                </h2>
                <?php echo $description ? '<div class="module-description"><p>'.esc_html($description).'</p></div>' : ''; ?>
            </div>
            <div class="col-12">
                <div class="card-list card-list-left">
                    <?php
                                foreach ($stacked_rows as $stacked_row_value) {
                                    $stacked_row = uamswp_field_array($stacked_row_value);
                                    // Load values.
                                    $image = (int) uamswp_field_text($stacked_row['stacked_section_image'] ?? null);
                                    $image_alt_native = uamswp_field_text(get_post_meta($image, '_wp_attachment_image_alt', true));
                                    $image_alt_override = uamswp_field_text($stacked_row['stacked_section_alt_override'] ?? null);
                                    $item_heading = uamswp_field_text($stacked_row['stacked_section_heading'] ?? null);
                                    $body = uamswp_field_text($stacked_row['stacked_section_body'] ?? null);
                                    $button_text = uamswp_field_text($stacked_row['stacked_section_button_text'] ?? null);
                                    $button_url = '';
                                    $button_target = '';
                                    $button_link = uamswp_field_array($stacked_row['stacked_section_button_url'] ?? null);
                                    if ($button_link !== []) {
                                        $button_url = uamswp_field_text($button_link['url'] ?? null);
                                        $button_target = uamswp_field_text($button_link['target'] ?? null);
                                    }
                                    $button_desc = uamswp_field_text($stacked_row['stacked_section_button_description'] ?? null);

                                    ?>
                        <div class="item">
                            <div class="card">
                                <div class="card-img-top">
                                    <picture>
                                        <?php if (function_exists('bis_get_attachment_image')) { ?>  
                                            <source srcset="<?php echo image_sizer($image, 455, 256, 'center', 'center', 'aspect-16-9-small'); ?>" media="(min-width: 1921px)">
                                            <source srcset="<?php echo image_sizer($image, 433, 244, 'center', 'center', 'aspect-16-9-small'); ?>" media="(min-width: 1500px)">
                                            <source srcset="<?php echo image_sizer($image, 455, 256, 'center', 'center', 'aspect-16-9-small'); ?>" media="(min-width: 992px)">
                                            <source srcset="<?php echo image_sizer($image, 433, 244, 'center', 'center', 'aspect-16-9-small'); ?>" media="(min-width: 768px)">
                                            <source srcset="<?php echo image_sizer($image, 455, 256, 'center', 'center', 'aspect-16-9-small'); ?>" media="(min-width: 1px)">
                                            <!-- Fallback -->
                                            <img src="<?php echo image_sizer($image, 455, 256, 'center', 'center', 'aspect-16-9-small'); ?>" alt="<?php echo esc_attr($image_alt_override ?: $image_alt_native); ?>" />
                                        <?php } else { ?>
                                            <!-- Fallback -->
                                            <img src="<?php echo wp_get_attachment_image_url($image, 'aspect-16-9-small'); ?>" alt="<?php echo esc_attr($image_alt_override ?: $image_alt_native); ?>" />
                                        <?php } // endif?>
                                    </picture>
                                </div>
                                <div class="card-body">
                                    <h3 class="card-title h5"><?php echo esc_html($item_heading); ?></h3>
                                    <p class="card-text"><?php echo esc_html($body); ?></p>
                                    <?php if ($button_text) { ?>  
                                        <a href="<?php echo esc_url($button_url); ?>" class="btn btn-primary stretched-link" aria-label="<?php echo esc_attr($button_desc); ?>"<?php echo $button_target ? ' target="'.esc_attr($button_target).'"' : ''; ?> data-moduletitle="<?php echo esc_attr($heading); ?>" data-itemtitle="<?php echo esc_attr($item_heading); ?>"><?php echo esc_html($button_text); ?></a>
                                    <?php } // endif?>
                                </div>
                            </div>
                        </div>
                    <?php } // end foreach?>
                </div>
            </div>
            <?php if ($more) { ?>
                <div class="col-12 more">
                    <p class="lead"><?php echo $more_text; ?></p>
                    <div class="cta-container">
                        <a href="<?php echo uamswp_field_text($more_button_link['url'] ?? null); ?>" class="btn btn-outline-<?php echo $more_button_color; ?>" aria-label="<?php echo $more_button_description; ?>"<?php echo $more_button_target ? ' target="'.esc_attr($more_button_target).'"' : ''; ?>><?php echo $more_button_text; ?></a>
                    </div>
                </div>
            <?php } // endif?>
        </div>
    </div>
</section>
<?php }
    }
