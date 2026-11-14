<?php

/** @var int|false $i Module index, set by uamswp_module() when this template is included as a module; an ACF block always has $block and an id, so the index is not read. */
/*
 *
 * UAMS Livewhale Calendar Block
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
    // @pest-mutate-ignore: ConcatRemoveLeft,ConcatSwitchSides,DecrementInteger,IncrementInteger
    $id = ! empty($moduleData['anchor_id']) ? sanitize_title_with_dashes(uamswp_field_text($moduleData['anchor_id'])) : 'module-'.((int) $i + 1);
}

// @pest-mutate-ignore: ConcatSwitchSides
$id = 'livewhale-'.uamswp_field_text($id);
if (! empty($blockData['anchor'])) {
    $id = $blockData['anchor'];
}

// $livewhale = '2';

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
    $heading = get_field('livewhale_heading');
}
if (empty($livewhale)) {
    $livewhale = get_field('livewhale_id');
}
if (empty($background_color)) {
    $background_color = get_field('livewhale_background_color');
}
if (empty($geo)) {
    $geo = get_field('livewhale_geo');
}
if (empty($geo_region)) {
    $geo_region = get_field('livewhale_geo_region');
}

// GEO Logic
$geo_region = uamswp_field_array($geo_region);
$geo_display = false;
if (! isset($geo) || empty($geo_region)) {
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
if (! empty($is_preview) && ! empty($geo) && ! empty($geo_region)) {
    $geo_display = true;
    echo esc_html(ucwords(uamswp_field_text($geo)).' region(s): '.implode(', ', array_map('uamswp_field_text', $geo_region))).'<hr>';
}
if ($geo_display) {
    ?>
<section class="uams-module link-list link-list-layout-split livewhale<?php echo esc_attr($className); ?> <?php echo esc_attr(uamswp_field_text($background_color)); ?>" id="<?php echo absint(uamswp_field_text($livewhale)); ?>" aria-label="<?php echo esc_attr(uamswp_field_text($heading)); ?>">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12 col-md-6 heading">
                <div class="text-container">
                    <h2 class="module-title">
                        <span class="title"><?php echo esc_html(uamswp_field_text($heading)); ?></span>
                    </h2>
                </div>
            </div>
            <div class="col-12 col-md-6 list">
                <!-- Livewhale Calendar Widget -->
                <?php if (! empty($is_preview)) { // The LiveWhale widget script doesn't run in editor previews.?>
                <p><em><?php echo esc_html(sprintf(__('LiveWhale calendar widget #%d displays here on the live page.'), absint(uamswp_field_text($livewhale)))); ?></em></p>
                <?php } else { ?>
                <div class="lwcw" data-options="id=<?php echo absint(uamswp_field_text($livewhale)); ?>&format=html"></div>
                <script type="text/javascript" id="lw_lwcw" src="https://calendar.uams.edu/livewhale/theme/core/scripts/lwcw.js"></script>
                <?php } ?>
            </div>
        </div>
    </div>
</section>
<?php }
