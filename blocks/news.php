<?php

declare(strict_types=1);

/** @var int|false $i Module index, set by uamswp_module() when this template is included as a module; an ACF block always has $block and an id, so the index is not read. */
/*
 *
 * UAMS News Block
 *
 */
$className = '';
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
$id = 'uams-news-'.uamswp_field_text($id);
if (! empty($blockData['anchor'])) {
    $id = $blockData['anchor'];
}

if (! empty($blockData['align'])) {
    // @pest-mutate-ignore: ConcatEqualToEqual
    $className .= ' align'.uamswp_field_text($blockData['align']);
}

// Load values.
if (empty($title)) {
    $title = get_field('news_title');
}

if (empty($hide_title)) {
    $hide_title = get_field('news_hide_title');
}

if (empty($output)) {
    $output = get_field('news_format');
}

if (empty($category)) {
    $category = get_field('news_category');
}

if (empty($count)) {
    $count = get_field('news_count');
}

if (empty($offset)) {
    $offset = get_field('news_offset');
}

if (empty($advancedCat)) {
    $advancedCat = get_field('news_advanced_cat');
}

if (empty($background_color)) {
    $background_color = get_field('news_background_color');
}

if (empty($hide_img)) {
    $hide_img = get_field('news_hide_img');
}

if (empty($hide_author)) {
    $hide_author = get_field('news_hide_author');
}

if (empty($hide_date)) {
    $hide_date = get_field('news_hide_date');
}

if (empty($articleID)) {
    $articleID = get_field('news_article_id');
}

if (empty($local)) {
    $local = get_field('news_local');
}

$link ??= get_field('news_include_link');

if (empty($position)) {
    $position = get_field('news_position');
}

if (empty($geo)) {
    $geo = get_field('news_geo');
}

if (empty($geo_region)) {
    $geo_region = get_field('news_geo_region');
}

if ($output == 'grid') {
    $count = '3';
} elseif ($output == 'cards') {
    $count = '4';
} elseif ($output == 'full') {
    $count = '1';
} elseif ($output == 'side') {
    $count = '1';
}

// echo '<!--[uamswp_news output="'. $output .'"  news_title="'. $title .'"  hide_title="'. $hide_title .'" category="'. $category .'" count="'. $count .'" offset="'. $offset .'" advanced_cat="'. $advancedCat .'" local="'. $local .'" style="'. $background_color . $className .'" hide_img="'. $hide_img .'" hide_author="'. $hide_author.'" hide_date="'. $hide_date .'" include_link="'. $link .'" news_position="'. $position .'" id="'. $articleID .'"]-->';
// echo '<!-- '; print_r($geo); echo ' -->';
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
    echo ucwords(uamswp_field_text($geo)).' region(s): '.implode(', ', array_map(uamswp_field_text(...), $geo_region)).'<hr>';
}

// Sanitize editor-controlled values before they are concatenated into the shortcode string.
$articleID = absint(uamswp_field_text($articleID));
$count = absint(uamswp_field_text($count));
$offset = absint(uamswp_field_text($offset));
$category = str_replace(['[', ']', '"'], '', sanitize_text_field(uamswp_field_text($category)));
$advancedCat = str_replace(['[', ']', '"'], '', sanitize_text_field(uamswp_field_text($advancedCat)));
$title = str_replace(['[', ']', '"'], '', sanitize_text_field(uamswp_field_text($title)));
if ($geo_display) {
    echo do_shortcode('[uamswp_news output="'.esc_attr(uamswp_field_text($output)).'"  news_title="'.esc_attr($title).'"  hide_title="'.esc_attr(uamswp_field_text($hide_title)).'" category="'.esc_attr($category).'" count="'.esc_attr((string) $count).'" offset="'.esc_attr((string) $offset).'" advanced_cat="'.esc_attr($advancedCat).'" local="'.esc_attr(uamswp_field_text($local)).'" style="'.esc_attr(uamswp_field_text($background_color).$className).'" hide_img="'.esc_attr(uamswp_field_text($hide_img)).'" hide_author="'.esc_attr(uamswp_field_text($hide_author)).'" hide_date="'.esc_attr(uamswp_field_text($hide_date)).'" include_link="'.esc_attr(uamswp_field_text($link)).'" news_position="'.esc_attr(uamswp_field_text($position)).'" id="'.esc_attr((string) $articleID).'"]');
}
