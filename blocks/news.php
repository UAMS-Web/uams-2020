<?php
/*
 *
 * UAMS News Block
 * 
 */

// Create id attribute allowing for custom "anchor" value.
if ( empty( $id ) )
    $id = 'uams-news-' . $block['id'];   

// Load values.
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
if ( empty($cache) )
    $cache = get_field('news_cache');
if ( empty($local) )
    $local = get_field('news_local');

if ( 'grid' == $output ) {
    $count = '3';
} elseif ( 'cards' == $output ) {
    $count = '4';
} elseif ( 'full' == $output ) {
    $count = '1';
}

echo '<!--[uamswp_news output="'. $output .'" category="'. $category .'" count="'. $count .'" offset="'. $offset .'" advanced_cat="'. $advancedCat .'" local="'. $local .'" style="'. $background_color .'" cache_bust="'. $cache .'"]-->';
echo do_shortcode('[uamswp_news output="'. $output .'" category="'. $category .'" count="'. $count .'" offset="'. $offset .'" advanced_cat="'. $advancedCat .'" local="'. $local .'" style="'. $background_color .'" cache_bust="'. $cache .'"]' );
?>
