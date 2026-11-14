<?php

/** @var int|false $i Module index, set by uamswp_module() when this template is included as a module; an ACF block always has $block and an id, so the index is not read. */
/*
 *
 * UAMS Link List Block
 *
 */

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
if (empty($hide_heading)) {
    $hide_heading = get_field('link_list_hide_heading');
}
if (empty($description)) {
    $description = get_field('link_list_description');
}
if (empty($background_color)) {
    $background_color = get_field('link_list_background_color');
}
if (empty($link_list_icons)) {
    $link_list_icons = get_field('link_list_icons');
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
    echo ucwords(uamswp_field_text($geo)).' region(s): '.implode(', ', array_map(uamswp_field_text(...), $geo_regions)).'<hr>';
}
if ($geo_display) {
    ?>
<section class="uams-module link-list<?php echo $className; ?> <?php echo uamswp_field_text($background_color); ?>" id="<?php echo $id; ?>" aria-label="<?php echo uamswp_field_text($heading); ?>">
    <h2 class="module-title <?php echo $hide_heading ? ' sr-only' : ''; ?>">
        <span class="title"><?php echo uamswp_field_text($heading); ?></span>
    </h2>
    <?php echo $description ? '<p class="note">'.uamswp_field_text($description).'</p>' : ''; ?>
    <div class="container-fluid">
        <div class="row">
        <?php
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
                    $link_icon = uamswp_field_text($link_list_row['link_list_section_icon'] ?? null);

                    ?>
            <div class="col item">
                <div class="inner-container">
                    <?php if ($link_list_icons) { ?>
                        <span class="fa-stack fa-lg">
                            <span class="fas fa-circle fa-stack-2x"></span>
                            <span class="<?php echo $link_icon ?: 'fas fa-link'; ?> fa-stack-1x fa-inverse"></span>
                        </span>
                    <?php } // endif?>
                    <h3 class="h5"><a class="stretched-link" href="<?php echo $link_url; ?>"<?php echo $link_target ? ' target="'.$link_target.'"' : ''; ?> aria-label="<?php echo $link_desc; ?>"><?php echo $link_title; ?></a></h3>
                    <?php echo $body ? '<p>'.$body.'</p>' : ''; ?>
                </div>
            </div>
        <?php
                }
    ?>
        </div>
    </div>
</section>
<?php }
