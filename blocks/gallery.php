<?php

/** @var int|false $i Module index, set by uamswp_module() when this template is included as a module; an ACF block always has $block and an id, so the index is not read. */
/*
 *
 * UAMS Gallery Block
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

$id = 'uams-gallery-'.uamswp_field_text($id);
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
    $heading = get_field('gallery_heading');
}
if (empty($hide_heading)) {
    $hide_heading = get_field('gallery_hide_heading');
}
if (empty($description)) {
    $description = get_field('gallery_description');
}
if (empty($gallery_columns)) {
    $gallery_columns = get_field('gallery_columns');
}
if (empty($gallery_images)) {
    $gallery_images = get_field('gallery_images');
}
if (empty($gallery_crop)) {
    $gallery_crop = get_field('gallery_crop');
}
if ($gallery_crop == 'none' || (uamswp_field_array($gallery_crop)[0] ?? null) == 'none') {
    $gallery_crop = -1;
}
if (empty($background_color)) {
    $background_color = get_field('gallery_background_color');
}
if (empty($geo)) {
    $geo = get_field('gallery_geo');
}
if (empty($geo_region)) {
    $geo_region = get_field('gallery_geo_region');
}
if (empty($modal)) {
    $modal = get_field('gallery_modal');
}
if (empty($more)) {
    $more = get_field('gallery_more');
}
if ($more) {
    if (empty($more_text)) {
        $more_text = get_field('gallery_more_text');
    }
    if (empty($more_button_text)) {
        $more_button_text = get_field('gallery_more_button_text');
    }
    if (empty($more_button_url)) {
        $more_button_url = get_field('gallery_more_button_url');
    }
    if (empty($more_button_target)) {
        $more_button_target = uamswp_field_array($more_button_url)['target'] ?? null;
    }
    if (empty($more_button_description)) {
        $more_button_description = get_field('gallery_more_button_description');
    }
    if (empty($more_button_color) && (in_array($background_color, ['bg-white', 'bg-gray', 'bg-auto'], true))) {
        $more_button_color = 'primary';
    } else {
        $more_button_color = 'white';
    }
}

$heading = uamswp_field_text($heading);
$description = uamswp_field_text($description);
$background_color = uamswp_field_text($background_color);
$more_text = uamswp_field_text($more_text ?? null);
$more_button_text = uamswp_field_text($more_button_text ?? null);
$more_button_link = uamswp_field_array($more_button_url ?? null);
$more_button_target = uamswp_field_text($more_button_target ?? null);
$more_button_description = uamswp_field_text($more_button_description ?? null);

if ($gallery_columns == '2') {
    $sm = 6;
    $md = $sm;
    $lg = 6;
} elseif ($gallery_columns == '3') {
    $sm = 6;
    $md = $sm;
    $lg = 4;
} elseif ($gallery_columns == '4') {
    $sm = 6;
    $md = $sm;
    $lg = 3;
} else { // $gallery_columns == '6'
    $sm = 6;
    $md = 4;
    $lg = 2;
}

// Ratio for image_sizer fallback
if ($gallery_crop == '1.7778') {
    $gallery_ratio = 'aspect-16-9';
} elseif ($gallery_crop == '1.3333') {
    $gallery_ratio = 'aspect-4-3';
} elseif ($gallery_crop == '1') { // @pest-mutate-ignore: ElseIfNegated,EqualToNotEqual
    $gallery_ratio = 'aspect-1-1';
} else {
    $gallery_ratio = 'full'; // Fallback to full if none or portait
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
<section class="uams-module gallery-block<?php echo $className; ?> <?php echo $background_color; ?>" id="<?php echo $id; ?>" aria-label="<?php echo esc_attr($heading); ?>">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12<?php echo ($hide_heading && empty($description)) ? ' sr-only' : ''; ?>">
                <h2 class="module-title<?php echo ($hide_heading && $description) ? ' sr-only' : ''; ?>">
                    <span class="title"><?php echo esc_html($heading); ?></span>
                </h2>
                <?php echo $description ? '<div class="module-description">'.wp_kses_post($description).'</div>' : ''; ?>
            </div>
            <div class="col-12 image-container padded-grid">
                <div class="row">
                    <?php
                        if (is_array($gallery_images)) {
                            $i = 0;
                            foreach ($gallery_images as $gallery_image_value) {
                                $gallery_image = uamswp_field_array($gallery_image_value);
                                // Load values.
                                $image_url = uamswp_field_text($gallery_image['url'] ?? null);
                                $image_alt = uamswp_field_text($gallery_image['alt'] ?? null);
                                $image_caption = uamswp_field_text($gallery_image['caption'] ?? null);
                                $image_md_url = uamswp_field_text(uamswp_field_array($gallery_image['sizes'] ?? null)['aspect-16-9'] ?? null);
                                $image_id = (int) uamswp_field_text($gallery_image['id'] ?? null);
                                /* <img class="w-100" src="<?php echo esc_url($image_url); ?>" alt="<?php echo $image_alt; ?>"> */
                                ?>
                                <div class="col-12 col-sm-<?php echo $sm ?> col-md-<?php echo $md ?> col-lg-<?php echo $lg; ?>">
                                        <?php if ($modal) { ?>
                                            <a href="#" data-toggle="modal" data-target="#modal_<?php echo $i; ?>_<?php echo $id; ?>" aria-label="Show larger version of image <?php echo $i + 1; ?><?php echo $image_alt ? ': '.esc_attr($image_alt) : ''; ?>">
                                        <?php } // endif?>
                                        <picture>
                                            <?php if (function_exists('bis_get_attachment_image')) { ?>  
                                                <source srcset="<?php echo image_sizer($image_id, gallery_image_dimension(
                                                    'xxl',
                                                    12 / $lg,
                                                    // Native densities 0 and 1 both leave the dimension unscaled.
                                                    // @pest-mutate-ignore: DecrementInteger
                                                    1
                                                ), gallery_image_dimension(
                                                    'xxl',
                                                    12 / $lg,
                                                    // Native densities 0 and 1 both leave the dimension unscaled.
                                                    // @pest-mutate-ignore: DecrementInteger
                                                    1,
                                                    $gallery_crop
                                                ), 'center', 'center'); ?>" 
                                                    media="(min-width: 1921px)">
                                                <source srcset="<?php echo image_sizer($image_id, gallery_image_dimension(
                                                    'xl',
                                                    12 / $lg,
                                                    // Native densities 0 and 1 both leave the dimension unscaled.
                                                    // @pest-mutate-ignore: DecrementInteger
                                                    1
                                                ), gallery_image_dimension(
                                                    'xl',
                                                    12 / $lg,
                                                    // Native densities 0 and 1 both leave the dimension unscaled.
                                                    // @pest-mutate-ignore: DecrementInteger
                                                    1,
                                                    $gallery_crop
                                                ), 'center', 'center'); ?>" 
                                                    media="(min-width: 1500px)">
                                                <source srcset="<?php echo image_sizer($image_id, gallery_image_dimension(
                                                    'lg',
                                                    12 / $lg,
                                                    // Native densities 0 and 1 both leave the dimension unscaled.
                                                    // @pest-mutate-ignore: DecrementInteger
                                                    1
                                                ), gallery_image_dimension(
                                                    'lg',
                                                    12 / $lg,
                                                    // Native densities 0 and 1 both leave the dimension unscaled.
                                                    // @pest-mutate-ignore: DecrementInteger
                                                    1,
                                                    $gallery_crop
                                                ), 'center', 'center'); ?>" 
                                                    media="(min-width: 1200px)">
                                                <source srcset="<?php echo image_sizer($image_id, gallery_image_dimension(
                                                    'md',
                                                    12 / $md,
                                                    // Native densities 0 and 1 both leave the dimension unscaled.
                                                    // @pest-mutate-ignore: DecrementInteger
                                                    1
                                                ), gallery_image_dimension(
                                                    'md',
                                                    12 / $md,
                                                    // Native densities 0 and 1 both leave the dimension unscaled.
                                                    // @pest-mutate-ignore: DecrementInteger
                                                    1,
                                                    $gallery_crop
                                                ), 'center', 'center'); ?>" 
                                                    media="(min-width: 992px)">
                                                <source srcset="<?php echo image_sizer($image_id, gallery_image_dimension(
                                                    'sm',
                                                    12 / $sm,
                                                    // Native densities 0 and 1 both leave the dimension unscaled.
                                                    // @pest-mutate-ignore: DecrementInteger
                                                    1
                                                ), gallery_image_dimension(
                                                    'sm',
                                                    12 / $sm,
                                                    // Native densities 0 and 1 both leave the dimension unscaled.
                                                    // @pest-mutate-ignore: DecrementInteger
                                                    1,
                                                    $gallery_crop
                                                ), 'center', 'center', ''); ?>" 
                                                    media="(min-width: 768px)">
                                                <source srcset="<?php echo image_sizer($image_id, gallery_image_dimension(
                                                    'xs',
                                                    1,
                                                    // Native densities 0 and 1 both leave the dimension unscaled.
                                                    // @pest-mutate-ignore: DecrementInteger
                                                    1
                                                ), gallery_image_dimension(
                                                    'xs',
                                                    1,
                                                    // Native densities 0 and 1 both leave the dimension unscaled.
                                                    // @pest-mutate-ignore: DecrementInteger
                                                    1,
                                                    $gallery_crop
                                                ), 'center', 'center', 'aspect-1-1'); ?>" 
                                                    media="(min-width: 1px)">
                                                <!-- Fallback -->
                                                <img src="<?php echo image_sizer($image_id, gallery_image_dimension(
                                                    'xl',
                                                    2,
                                                    // Native densities 0 and 1 both leave the dimension unscaled.
                                                    // @pest-mutate-ignore: DecrementInteger
                                                    1
                                                ), gallery_image_dimension(
                                                    'xl',
                                                    2,
                                                    // Native densities 0 and 1 both leave the dimension unscaled.
                                                    // @pest-mutate-ignore: DecrementInteger
                                                    1,
                                                    $gallery_crop
                                                ), 'center', 'center'); ?>" alt="<?php echo esc_attr($image_alt); ?>" />
                                            <?php } else { ?>
                                                <!-- Fallback -->
                                                <img src="<?php echo wp_get_attachment_image_url($image_id, 'aspect-16-9'); ?>" alt="<?php echo esc_attr($image_alt); ?>" />
                                            <?php } // endif?>
                                        </picture>
                                    <?php if ($modal) { ?>
                                        </a>
                                    <?php } // endif?>
                                </div>
                                <?php if ($modal) { ?>
                                    <div class="modal fade" id="modal_<?php echo $i; ?>_<?php echo $id; ?>" tabindex="-1" role="dialog" aria-label="Larger version of image <?php echo $i + 1; ?><?php echo $image_alt ? ': '.esc_attr($image_alt) : ''; ?>" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered modal-xl" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                                <div class="modal-body">
                                                    <figure class="figure">
                                                        <?php if (function_exists('bis_get_attachment_image')) { ?>
                                                            <picture>
                                                                <source srcset="<?php echo image_sizer($image_id, 1106, -1, 'center', 'center'); ?>" media="(min-width: 1200px)">
                                                                <source srcset="<?php echo image_sizer($image_id, 1094, -1, 'center', 'center'); ?>" media="(min-width: 992px)">
                                                                <source srcset="<?php echo image_sizer($image_id, 886, -1, 'center', 'center'); ?>" media="(min-width: 768px)">
                                                                <source srcset="<?php echo image_sizer($image_id, 702, -1, 'center', 'center'); ?>" media="(min-width: 1px)">
                                                                <!-- Fallback -->
                                                                <img src="<?php echo image_sizer($image_id, 1106, -1, 'center', 'center'); ?>" alt="<?php echo esc_attr($image_alt); ?>" />
                                                            </picture>
                                                        <?php } else { ?>
                                                            <img src="<?php echo wp_get_attachment_image_url($image_id, 'content-image-full'); ?>" alt="<?php echo esc_attr($image_alt); ?>">
                                                        <?php } // endif
                                    if ($image_caption) { ?>
                                                            <figcaption class="figure-caption"><?php echo wp_kses_post($image_caption); ?></figcaption>
                                                        <?php } // endif?>
                                                    </figure>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                <?php } // endif?>
                            <?php
                        $i++;
                            }
                        }
    ?>
                </div>
            </div>
            <?php if ($more) { ?>
                <div class="col-12 more">
                    <p class="lead"><?php echo $more_text; ?></p>
                    <div class="cta-container">
                        <a href="<?php echo esc_url(uamswp_field_text($more_button_link['url'] ?? null)); ?>" class="btn btn-<?php echo $more_button_color; ?>" aria-label="<?php echo esc_attr($more_button_description); ?>"<?php echo $more_button_target ? ' target="'.esc_attr($more_button_target).'"' : ''; ?>><?php echo $more_button_text; ?></a>
                    </div>
                </div>
            <?php } // endif?>
        </div>
    </div>
</section>
<?php }
