<?php

/** @var int|false $i Module index, set by uamswp_module() when this template is included as a module; an ACF block always has $block and an id, so the index is not read. */
/*
 *
 * UAMS Action Bar Block
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

$id = 'action-bar-'.uamswp_field_text($id);
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
    $heading = get_field('action_bar_heading');
}
if (empty($background_color)) {
    $background_color = get_field('action_bar_background_color');
}
if (empty($action_bar_rows)) {
    $action_bar_rows = get_field('action_bar_section');
}
if (empty($geo)) {
    $geo = get_field('action_bar_geo');
}
if (empty($geo_region)) {
    $geo_region = get_field('action_bar_geo_region');
}

$action_bar_rows = uamswp_field_array($action_bar_rows);
$row_count = count($action_bar_rows);
$background_color = uamswp_field_text($background_color);
$heading = uamswp_field_text($heading);
if (in_array($background_color, ['bg-white', 'bg-gray', 'bg-auto'], true)) {
    $btn_color = 'primary';
} else {
    $btn_color = 'white';
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
    echo esc_html(ucwords(uamswp_field_text($geo))).' region(s): '.esc_html(implode(', ', array_map(uamswp_field_text(...), $geo_regions))).'<hr>';
}
if ($geo_display) {
    ?>
<section class="uams-module action-bar<?php echo esc_attr($className); ?> count-<?php echo $row_count < 4 ? '3' : '4'; ?> <?php echo esc_attr($background_color); ?>" id="<?php echo esc_attr($id); ?>" aria-label="<?php echo esc_attr($heading); ?>">
    <h2 class="sr-only"><?php echo esc_html($heading); ?></h2>
    <div class="container-fluid">
        <div class="row">
<?php
        foreach ($action_bar_rows as $action_bar_row_value) {
            $action_bar_row = uamswp_field_array($action_bar_row_value);
            // Load values.
            $section_heading = uamswp_field_text($action_bar_row['action_bar_section_heading'] ?? null);
            $body = uamswp_field_text($action_bar_row['action_bar_section_body'] ?? null);
            $button_text = uamswp_field_text($action_bar_row['action_bar_section_button_text'] ?? null);
            $button_url = '';
            $button_target = '';
            $button_link = uamswp_field_array($action_bar_row['action_bar_section_button_url'] ?? null);
            if ($button_link !== []) {
                $button_url = uamswp_field_text($button_link['url'] ?? null);
                $button_target = uamswp_field_text($button_link['target'] ?? null);
            }
            $button_desc = uamswp_field_text($action_bar_row['action_bar_section_button_description'] ?? null);

            ?>
            <div class="col-12 <?php echo $row_count < 4 ? 'col-sm-4' : 'col-md-3'; ?> item">
                <div class="inner-container">
                    <div class="text-container">
                        <h3 class="h5" data-moduletitle="<?php echo esc_attr($heading); ?>"><?php echo esc_html($section_heading); ?></h3>
                        <p><?php echo esc_html($body); ?></p>
                    </div>
                    <a class="btn btn-<?php echo esc_attr($btn_color); ?>" href="<?php echo esc_url($button_url); ?>" aria-label="<?php echo esc_attr($button_desc); ?>"<?php echo $button_target ? ' target="'.esc_attr($button_target).'"' : ''; ?> data-moduletitle="<?php echo esc_attr($heading); ?>" data-itemtitle="<?php echo esc_attr($section_heading); ?>"><?php echo esc_html($button_text); ?></a>
                </div>
            </div>
    <?php
        }
    ?>
        </div>
    </div>
</section>
<?php }
