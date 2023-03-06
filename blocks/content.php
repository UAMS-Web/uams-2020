<?php
/*
 *
 * UAMS Content Block
 * 
 */

// Create id attribute allowing for custom "anchor" value.
if (empty( $id )) {
	$id = '';
}
if (empty( $i )) {
	$i = 0;
}
if ( empty( $id ) && isset($block) ) {
    $id = $block['id'];
} 
if ( empty ($id) ) {
    $id = !empty( $module['anchor_id'] ) ? sanitize_title_with_dashes( $module['anchor_id'] ) : 'module-' . ( $i + 1 );
}

$id = 'content-' . $id;
if( !empty($block['anchor']) ) {
    $id = $block['anchor'];
}  
    
$className = '';
if( !empty($block['className']) ) {
    $className .= ' ' . $block['className'];
}
if( !empty($block['align']) ) {
    $className .= ' align' . $block['align'];
}  

// Load values.
if ( empty($heading) && get_field('content_heading') ) {
    $heading = get_field('content_heading');
} elseif ( empty($heading) && get_sub_field('content_heading') ) {
    $heading = get_sub_field('content_heading');
}
if ( empty($hide_heading) && get_field('content_hide_heading') ) {
    $hide_heading = get_field('content_hide_heading');
} elseif ( empty($hide_heading) && get_sub_field('content_hide_heading') ) {
    $hide_heading = get_sub_field('content_hide_heading');
} else {
    $hide_heading = '';
}
if ( empty($content_block) && get_field('content_content') ) {
    $content_block = get_field('content_content');
} elseif ( empty($content_block) && get_sub_field('content_content') ) {
    $content_block = get_sub_field('content_content');
}
if ( empty($background_color) && get_field('content_background_color') ) {
    $background_color = get_field('content_background_color');
} elseif ( empty($background_color) && get_sub_field('content_background_color') ) {
    $background_color = get_sub_field('content_background_color');
}
if ( empty($geo) )
    $geo = get_field('content_geo');
if ( empty($geo_region) )
    $geo_region = get_field('content_geo_region');

// GEO Logic
$geo_display = false;
if (!isset($geo) || empty($geo_region)){
    $geo_display = true;
} else {
    if( $geo == 'include' && !empty($geo_region) ) {
        if( is_in_region($geo_region) ){
            $geo_display = true;
        }
    } elseif( $geo == 'exclude' && !empty($geo_region) ) {
        if ( is_not_in_region($geo_region) ){
            $geo_display = true;
        }
    }
}
if (is_admin() && !empty($geo) && !empty($geo_region)) {
    $geo_display = true;
    echo ucwords($geo) . ' region(s): ' . implode(', ', $geo_region) . '<hr>';
}
if ($geo_display) :
?>
<section class="uams-module content-block<?php echo $className; ?> <?php echo $background_color; ?>" id="<?php echo $id; ?>" aria-label="<?php echo $heading; ?>">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <h2 class="module-title <?php echo $hide_heading ? " sr-only" : ""; ?>">
                    <span class="title"><?php echo $heading; ?></span>
                </h2>
                <?php echo $content_block ? '<div class="module-body">'. $content_block .'</div>' : ''; ?>
            </div>
        </div>
    </div>
</section>
<?php endif;