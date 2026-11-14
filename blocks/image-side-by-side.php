<?php

/** @var int|false $i Module index, set by uamswp_module() when this template is included as a module; an ACF block always has $block and an id, so the index is not read. */
/*
 *
 * UAMS Image Side-by-side Block
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

$id = 'image-side-'.uamswp_field_text($id);
if (! empty($blockData['anchor'])) {
    $id = uamswp_field_text($blockData['anchor']);
}
$id = sanitize_html_class($id);

// Create class attribute allowing for custom "className" and "align" values.
$className = 'uams-side-by-side-block';
if (! empty($blockData['className'])) {
    $className .= ' '.uamswp_field_text($blockData['className']);
}
if (! empty($blockData['align'])) {
    $className .= ' align'.uamswp_field_text($blockData['align']);
}

// Load values and setting defaults.
// If values are empty => used for modules & widgets
if (empty($layout)) {
    $layout = get_field('side_text_layout') ?: 'link-list';
}
if (empty($heading)) {
    $heading = get_field('side_heading') ?: 'Heading goes here...';
}
$layout = uamswp_field_text($layout);
$heading = uamswp_field_text($heading);
$is_quote = ($layout === 'blockquote-citation' || $layout === 'blockquote-citation-link');

if (empty($body)) {
    if ($layout === 'body-only') {
        $body = get_field('side_layout_body_text') ?: 'This is where the body-only description goes';
    } elseif ($is_quote) {
        $body = ''; // The quote layouts show a quote and citation instead of a body.
    } else {
        $body = get_field('side_layout_link_text') ?: 'This is where the body + link list description goes';
    }
}

$body = uamswp_field_text($body);

if ($is_quote) {
    if (empty($quote_text)) {
        $quote_text = get_field($layout === 'blockquote-citation-link' ? 'side_quote_text_link' : 'side_quote_text') ?: 'This is where the quote goes';
    }
    // Strip quotation marks typed at the start or end. Curly quotes are added on output.
    $quote_text = preg_replace('/^[\s"\'“”‘’]+|[\s"\'“”‘’]+$/u', '', uamswp_field_text($quote_text)) ?? uamswp_field_text($quote_text);
    if (empty($quote_name)) {
        $quote_name = get_field('side_quote_speaker_name') ?: 'Speaker name';
    }
    if (empty($quote_title)) {
        $quote_title = get_field('side_quote_speaker_title') ?: '';
    }
    $quote_name = uamswp_field_text($quote_name);
    $quote_title = uamswp_field_text($quote_title);
}

if (empty($link_list)) {
    // @pest-mutate-ignore: EmptyStringToNotEmpty
    $link_list = get_field('side_link_list') ?: ''; // A non-array default reads as no rows either way.
}
if (empty($list_more)) {
    $list_more = get_field('side_link_include_more') ?: '';
}
$link_rows = uamswp_field_array($link_list);
if (empty($cta)) {
    // @pest-mutate-ignore: EmptyStringToNotEmpty
    $cta = get_field('side_cta') ?: '';
}
// A layout that hides the button (Blockquote with Citation) saves no button fields.
if (! is_array($cta)) {
    $cta = [];
}
// A non-array default reads as no link (typed to an empty array) either way.
// @pest-mutate-ignore: EmptyStringToNotEmpty
$cta += ['side_cta_text' => '', 'side_cta_url' => '', 'side_cta_description' => ''];
$cta_text = uamswp_field_text($cta['side_cta_text']);
$cta_link = '';
$cta_target = '';
$cta_url = uamswp_field_array($cta['side_cta_url']);
if ($cta_url !== []) {
    $cta_link = uamswp_field_text($cta_url['url'] ?? null);
    $cta_target = uamswp_field_text($cta_url['target'] ?? null);
}
$cta_desc = uamswp_field_text($cta['side_cta_description']);
if ($layout === 'blockquote-citation') {
    $cta_text = ''; // This layout has no button, even if one was saved under another layout.
}
if (empty($image_group)) {
    // @pest-mutate-ignore: EmptyStringToNotEmpty
    $image_group = get_field('side_image') ?: '';
}
if (! is_array($image_group)) {
    $image_group = [];
}
// A non-numeric default reads as image id 0, the same as the empty string.
// @pest-mutate-ignore: EmptyStringToNotEmpty
$image_group += ['side_image_image' => '', 'side_image_alt_text' => '', 'side_image_crop' => '', 'side_image_anchor' => ''];
$side_image = (int) uamswp_field_text($image_group['side_image_image']);
$image_alt = uamswp_field_text($image_group['side_image_alt_text']);
$image_crop = uamswp_field_text($image_group['side_image_crop']);
$image_anchor = uamswp_field_text($image_group['side_image_anchor']) ?: 'center';
if (empty($image_postion)) {
    $image_postion = get_field('side_image_position') ?: 'left';
}
if (empty($background_color)) {
    $background_color = get_field('side_image_background_color') ?: 'bg-white';
}
if (empty($geo)) {
    $geo = get_field('side_image_geo');
}
if (empty($geo_region)) {
    $geo_region = get_field('side_image_geo_region');
}
if (empty($image_alt)) {
    $image_alt = uamswp_field_text(get_post_meta($side_image, '_wp_attachment_image_alt', true));
}
$image_postion = uamswp_field_text($image_postion);
$background_color = uamswp_field_text($background_color);
$cta_target = $cta_target ? ' target="'.esc_attr($cta_target).'"' : '';
$cta_desc = $cta_desc ? ' aria-label="'.esc_attr($cta_desc).'"' : '';
$cta_link = $cta_link ? '<a class="btn btn-primary" href="'.esc_url($cta_link).'"'.$cta_desc.$cta_target.' data-moduletitle="'.esc_attr($heading).'">' : '';
// @pest-mutate-ignore: DecrementInteger,EmptyStringToNotEmpty,IncrementInteger
$side_image_width = wp_get_attachment_image_src($side_image, 'full')[1] ?? '';
// $side_image_height = wp_get_attachment_image_src($side_image, 'full')[2];

// Break it if it isn't "Landing" template
// $page_template = get_page_template_slug( $post_id );
// // echo $page_template;
// if ('templates/page_landing.php' != $page_template) {
//     echo '<h4>This template is not supported. Please select "Landing"'. $page_template .'</h4>';
//     return;
// }

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
<section class="uams-module no-padding side-by-side <?php echo esc_attr($className); ?> image-on-<?php echo esc_attr($image_postion); ?> image-background-<?php echo esc_attr($image_anchor); ?> <?php echo esc_attr($background_color); ?>" id="side-by-side-<?php echo esc_attr($id); ?>" aria-label="<?php echo esc_attr($heading); ?>">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12 col-md-6 image-container" aria-label="<?php echo esc_attr($image_alt) ?>" role="img">

                <?php if (function_exists('bis_get_attachment_image')) { ?>
                <style>
                    #side-by-side-<?php echo esc_attr($id); ?> .image-container {
                        background-image: url("<?php echo image_sizer($side_image, 576, 324, $image_anchor, $image_crop, 'aspect-16-9-small'); ?>");
                    }

                    /* XS Breakpoint */
                    @media (min-width: 576px) {
                        #side-by-side-<?php echo esc_attr($id); ?> .image-container {
                            background-image: url("<?php echo image_sizer($side_image, 768, 432, $image_anchor, $image_crop, 'aspect-16-9-small'); ?>");
                        }
                    }

                    /* SM Breakpoint */
                    @media (min-width: 768px) {
                        #side-by-side-<?php echo esc_attr($id); ?> .image-container {
                            background-image: url("<?php echo image_sizer($side_image, 992, 558, $image_anchor, $image_crop, 'aspect-16-9'); ?>");
                        }
                    }

                    /* MD Breakpoint */
                    @media (min-width: 992px) {
                        #side-by-side-<?php echo esc_attr($id); ?> .image-container {
                            background-image: url("<?php echo image_sizer($side_image, 1200, 675, $image_anchor, $image_crop, 'aspect-16-9'); ?>");
                        }
                    }

                    /* LG Breakpoint */
                    @media (min-width: 1200px) {
                        #side-by-side-<?php echo esc_attr($id); ?> .image-container {
                            background-image: url("<?php echo image_sizer($side_image, 1500, 844, $image_anchor, $image_crop, 'aspect-16-9'); ?>");
                        }
                    }

                    /* XL Breakpoint */
                    @media (min-width: 1500px) {
                        #side-by-side-<?php echo esc_attr($id); ?> .image-container {
                            background-image: url("<?php echo image_sizer($side_image, 1921, 1081, $image_anchor, $image_crop, 'aspect-16-9'); ?>");
                        }
                    }

                    /* XXL Breakpoint */
                    @media (min-width: 1921px) {
                        #side-by-side-<?php echo esc_attr($id); ?> .image-container {
                            background-image: url("<?php echo image_sizer($side_image, 2560, 1440, $image_anchor, $image_crop, 'aspect-16-9'); ?>");
                        }
                    }
                </style>
                <?php } else { ?>
                <style>
                    #side-by-side-<?php echo esc_attr($id); ?> .image-container {
                        background-image: url("<?php echo wp_get_attachment_url($side_image); ?>");
                    }
                </style>
                <?php } // endif?>
                <div class="image-inner-container">
                </div>
            </div>
            <div class="col-12 col-md-6 text-container">
                <div class="text-inner-container">
                    <h2 class="h3"><?php echo esc_html($heading); ?></h2>
                    <?php echo ! empty($body) ? '<p>'.esc_html($body).'</p>' : ''; ?>
                    <?php if ($is_quote) { ?>
                    <figure>
                        <blockquote>
                            <p>“<?php echo esc_html($quote_text); ?>”</p>
                        </blockquote>
                        <figcaption class="blockquote-footer">
                            <?php echo esc_html($quote_name); ?><?php if ($quote_title) { ?><br>
                            <?php echo esc_html($quote_title); ?><?php } ?>

                        </figcaption>
                    </figure>
                    <?php } ?>
                    <?php if ($layout === 'link-list' && $link_rows !== []) { ?>
                    <ul>
                        <?php foreach ($link_rows as $link_value) {
                            $link = uamswp_field_array($link_value);
                            $list_text = uamswp_field_text($link['side_link_list_text'] ?? null);
                            $list_url = uamswp_field_text(uamswp_field_array($link['side_link_list_url'] ?? null)['url'] ?? null);
                            $list_desc = uamswp_field_text($link['side_link_list_description'] ?? null);
                            ?>
                        <li>
                            <?php if ($list_url) { ?>
                            <a href="<?php echo esc_url($list_url); ?>"<?php echo $list_desc ? ' aria-label="'.esc_attr($list_desc).'"' : ''; ?> data-moduletitle="<?php echo esc_attr($heading); ?>">
                            <?php } ?>
                            <?php if ($list_text) { ?>
                                <?php echo esc_html($list_text); ?>
                            <?php } ?>
                            <?php if ($list_url) { ?>
                            </a>
                            <?php } ?>
                        </li>
                        <?php } // end foreach?>
                        <?php echo $list_more ? '<li><em>and more</em></li>' : ''; ?>
                    </ul>
                    <?php }
                    if ($cta_text) {
                        echo $cta_link;
                        echo esc_html($cta_text);
                        echo '</a>';
                    } ?>
                </div>
            </div>
        </div>
    </div>
</section>
<?php }
