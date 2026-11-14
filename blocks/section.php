<?php

/** @var int|false $i Module index, set by uamswp_module() when this template is included as a module; an ACF block always has $block and an id, so the index is not read. */
/*
 *
 * UAMS Section Block
 *
 */
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
$id = 'uams-section-'.uamswp_field_text($id);
if (! empty($blockData['anchor'])) {
    $id = $blockData['anchor'];
}

if (empty($heading)) {
    $heading = get_field('section_heading');
}
if (empty($hide_heading)) {
    $hide_heading = get_field('section_hide_heading');
}
if (empty($background_color)) {
    $background_color = get_field('section_background_color');
}

$className = ['uams-module', 'section-block', 'block-section', 'alignfull'];
if (! empty($blockData['className'])) {
    $className = array_merge($className, explode(' ', uamswp_field_text($blockData['className'])));
}
$className = array_filter(array_unique($className));

// NOTE: this allow-list only constrains the block editor UI (the InnerBlocks inserter).
// It is not enforced when content is saved or created over REST. See #575.
$allowed_blocks = uamswp_section_allowed_blocks();

$template = [
];
?>  
<section class="<?php echo esc_attr(implode(' ', $className)); ?> <?php echo esc_attr(uamswp_field_text($background_color)); ?>" id="<?php echo esc_attr(uamswp_field_text($id)); ?>" aria-label="<?php echo esc_attr(uamswp_field_text($heading)); ?>">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12<?php echo $hide_heading ? ' sr-only' : ''; ?>">
                <h2 class="module-title<?php echo $hide_heading ? ' sr-only' : ''; ?>">
                    <span class="title"><?php echo esc_html(uamswp_field_text($heading)); ?></span>
                </h2>
            </div>
            <div class="module-body">
                <?php echo '<InnerBlocks allowedBlocks="'.esc_attr(uamswp_field_text(wp_json_encode($allowed_blocks))).'" template="'.esc_attr(uamswp_field_text(wp_json_encode($template))).'" />'; ?>
            </div>
        </div>
    </div>
</section>