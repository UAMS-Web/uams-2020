<?php

/** @var int|false $i Module index, set by uamswp_module() when this template is included as a module; an ACF block always has $block and an id, so the index is not read. */
/*
 *
 * UAMS Content Block
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

$id = 'content-'.uamswp_field_text($id);
if (! empty($blockData['anchor'])) {
    $id = $blockData['anchor'];
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
    $heading = get_field('content_heading');
}
if (empty($hide_heading)) {
    $hide_heading = get_field('content_hide_heading');
}
if (empty($content_block)) {
    $content_block = get_field('content_content');
}
if (empty($background_color)) {
    $background_color = get_field('content_background_color');
}
if (empty($geo)) {
    $geo = get_field('content_geo');
}
if (empty($geo_region)) {
    $geo_region = get_field('content_geo_region');
}

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
    ?>
<section class="uams-module content-block<?php echo esc_attr($className); ?> <?php echo esc_attr(uamswp_field_text($background_color)); ?>" id="<?php echo esc_attr(uamswp_field_text($id)); ?>" aria-label="<?php echo esc_attr(uamswp_field_text($heading)); ?>">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <h2 class="module-title <?php echo $hide_heading ? ' sr-only' : ''; ?>">
                    <span class="title"><?php echo esc_html(uamswp_field_text($heading)); ?></span>
                </h2>
                <?php echo $content_block ? '<div class="module-body">'.wp_kses_post(uamswp_field_text($content_block)).'</div>' : ''; ?>
            </div>
        </div>
    </div>
</section>
<?php }
