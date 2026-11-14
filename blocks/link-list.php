<?php

/** @var int|false $i Module index, set by uamswp_module() when this template is included as a module; an ACF block always has $block and an id, so the index is not read. */
/*
 *
 * UAMS Link List Block
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

$id = 'link-list-'.uamswp_field_text($id);
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
    $heading = get_field('link_list_heading');
}
if (empty($description)) {
    $description = get_field('link_list_description');
}
if (empty($background_color)) {
    $background_color = get_field('link_list_background_color');
}
if (empty($link_list_rows)) {
    $link_list_rows = get_field('link_list_section');
}
if (empty($geo)) {
    $geo = get_field('link_list_geo');
}
if (empty($geo_region)) {
    $geo_region = get_field('link_list_geo_region');
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
<section class="uams-module link-list link-list-layout-split<?php echo esc_attr($className); ?> <?php echo esc_attr(uamswp_field_text($background_color)); ?>" id="<?php echo esc_attr($id); ?>" aria-label="<?php echo esc_attr(uamswp_field_text($heading)); ?>">

    <div class="container-fluid">
        <div class="row">
            <div class="col-12 col-md-6 heading">
                <div class="text-container">
                    <h2 class="module-title">
                        <span class="title"><?php echo esc_html(uamswp_field_text($heading)); ?></span>
                    </h2>
                    <?php echo $description ? '<p class="note">'.esc_html(uamswp_field_text($description)).'</p>' : ''; ?>
                </div>
            </div>
            <div class="col-12 col-md-6 list">
                    <ul>
                    <?php
                        if ($link_list_rows) {
                            foreach (uamswp_field_array($link_list_rows) as $link_list_row_value) {
                                // Load values.
                                $link_list_row = uamswp_field_array($link_list_row_value);
                                $link_title = uamswp_field_text($link_list_row['link_list_section_title'] ?? null);
                                $body = uamswp_field_text($link_list_row['link_list_section_body'] ?? null);
                                $link_url = '';
                                $link_target = '';
                                $link_field = uamswp_field_array($link_list_row['link_list_section_url'] ?? null);
                                if ($link_field !== []) {
                                    $link_url = uamswp_field_text($link_field['url'] ?? null);
                                    $link_target = uamswp_field_text($link_field['target'] ?? null);
                                }
                                $link_desc = uamswp_field_text($link_list_row['link_list_section_description'] ?? null);
                                ?>
                        <li class="item">
                            <div class="text-container">
                                <h3 class="h5"><a class="stretched-link" href="<?php echo esc_url($link_url); ?>"<?php echo $link_target ? ' target="'.esc_attr($link_target).'"' : ''; ?> aria-label="<?php echo esc_attr($link_desc); ?>" data-moduletitle="<?php echo esc_attr(uamswp_field_text($heading)); ?>"><?php echo esc_html($link_title); ?></a></h3>
                                <?php echo $body ? '<p>'.esc_html($body).'</p>' : ''; ?>
                            </div>
                        </li>
                    <?php
                            }
                        }
    ?>
                </ul>
            </div>
        </div>
    </div>
    <div class="container-fluid">
        <div class="row">
        </div>
    </div>
</section>
<?php }
