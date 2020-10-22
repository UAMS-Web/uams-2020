<?php
/*
 *
 * UAMS News Block
 *
 */
$className = '';
// Create id attribute allowing for custom "anchor" value.
$id = '';
if ( empty( $id ) && isset($block) ) {
    $id = $block['id'];
}
if ( empty ($id) ) {
    $id = !empty( $module['anchor_id'] ) ? sanitize_title_with_dashes( $module['anchor_id'] ) : 'module-' . ( $i + 1 );
}

$id = 'uams-news-' .  $id;

if( !empty($block['align']) ) {
    $className .= ' align' . $block['align'];
}

// Load values.
if ( empty($title) )
    $title = get_field('news_title');
if ( empty($hide_title) )
    $hide_title = get_field('news_hide_title');
if ( empty($output) )
    $output = get_field('news_format');
if ( empty($category) )
    $category = get_field('news_category');
if ( empty($count) )
    $count = get_field('news_count');
if ( empty($offset) )
    $offset = get_field('news_offset');
if ( empty($advancedCat) )
    $advancedCat = get_field('news_advanced_cat');
if ( empty($background_color) )
    $background_color = get_field('news_bgcolor');
if ( empty($hide_img) )
    $hide_img = get_field('news_hide_img');
if ( empty($articleID) )
    $articleID = get_field('news_article_id');
if ( empty($local) )
    $local = get_field('news_local');
if ( empty($link) )
    $link = get_field('news_include_link');
if ( empty($position) )
    $position = get_field('news_position');
if ( empty($geo) )
    $geo = get_field('news_geo');

if ( 'grid' == $output ) {
    $count = '3';
} elseif ( 'cards' == $output ) {
    $count = '4';
} elseif ( 'full' == $output ) {
    $count = '1';
} elseif ( 'side' == $output ) {
    $count = '1';
}

// echo '<!--[uamswp_news output="'. $output .'"  news_title="'. $title .'"  hide_title="'. $hide_title .'" category="'. $category .'" count="'. $count .'" offset="'. $offset .'" advanced_cat="'. $advancedCat .'" local="'. $local .'" style="'. $background_color . $className .'" hide_img="'. $hide_img .'" include_link="'. $link .'" news_position="'. $position .'" id="'. $articleID .'"]-->';
// echo '<!-- '; print_r($geo); echo ' -->';
// GEO Logic
$geo_display = false;
if (!isset($geo)){
    $geo_display = true;
} else {
    if( $geo['geot_condition'] == 'include' ) {
        if( geot_target_city( '', $geo['geot_city_regions'] ) ){
            $geo_display = true;
        }
    }  else {
        if ( geot_target_city( '', '', '', $geo['geot_city_regions'] ) ){
            $geo_display = true;
        }
    }
}
if ($geo_display) {
	echo do_shortcode('[uamswp_news output="'. $output .'"  news_title="'. $title .'"  hide_title="'. $hide_title .'" category="'. $category .'" count="'. $count .'" offset="'. $offset .'" advanced_cat="'. $advancedCat .'" local="'. $local .'" style="'. $background_color . $className .'" hide_img="'. $hide_img .'" include_link="'. $link .'" news_position="'. $position .'" id="'. $articleID .'"]' );
}
?>