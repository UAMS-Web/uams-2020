<?php
/*
 *
 * UAMS Livewhale Calendar Block
 * 
 */

// Create id attribute allowing for custom "anchor" value.
if (empty( $id )) {
	$id = '';
}
if ( empty( $id ) && isset($block) ) {
    $id = $block['id'];
} 
if ( empty ($id) ) {
    $id = !empty( $module['anchor_id'] ) ? sanitize_title_with_dashes( $module['anchor_id'] ) : 'module-' . ( $i + 1 );
}

$id = 'livewhale-' . $id;  

// $livewhale = '2';
    
$className = '';
if( !empty($block['className']) ) {
    $className .= ' ' . $block['className'];
}
if( !empty($block['align']) ) {
    $className .= ' align' . $block['align'];
}  

// Load values.
if ( empty($heading) )
    $heading = get_field('livewhale_heading');
if ( empty($livewhale) )
    $livewhale = get_field('livewhale_id');
if ( empty($background_color) )
    $background_color = get_field('livewhale_background_color');
if ( empty($geo) )
    $geo = get_field('livewhale_geo');
if ( empty($geo_region) )
    $geo_region = get_field('livewhale_geo_region');

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
<section class="uams-module link-list link-list-layout-split livewhale<?php echo $className; ?> <?php echo $background_color; ?>" id="<?php echo $livewhale; ?>" aria-label="<?php echo $heading; ?>">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12 col-md-6 heading">
                <div class="text-container">
                    <h2 class="module-title">
                        <span class="title"><?php echo $heading; ?></span>
                    </h2>
                </div>
            </div>
            <div class="col-12 col-md-6 list">
                <!-- Livewhale Calendar Widget -->
                <div class="lwcw" data-options="id=<?php echo $livewhale; ?>&format=html"></div> 
                <script type="text/javascript" id="lw_lwcw" src="https://calendar.uams.edu/livewhale/theme/core/scripts/lwcw.js"></script>
            </div>
        </div>
    </div>
</section>
<?php endif;