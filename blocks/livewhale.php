<?php
/*
 *
 * UAMS Livewhale Calendar Block
 * 
 */

// Editor placeholder for a newly inserted, still-empty block (ACF Blocks V3).
if ( uamswp_block_placeholder( $block ?? null, $is_preview ?? false ) ) {
    return;
}

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
if( !empty($block['anchor']) ) {
    $id = $block['anchor'];
} 

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
if (!empty($is_preview) && !empty($geo) && !empty($geo_region)) {
    $geo_display = true;
    echo esc_html(ucwords($geo) . ' region(s): ' . implode(', ', $geo_region)) . '<hr>';
}
if ($geo_display) :
?>
<section class="uams-module link-list link-list-layout-split livewhale<?php echo esc_attr($className); ?> <?php echo esc_attr($background_color); ?>" id="<?php echo absint($livewhale); ?>" aria-label="<?php echo esc_attr($heading); ?>">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12 col-md-6 heading">
                <div class="text-container">
                    <h2 class="module-title">
                        <span class="title"><?php echo esc_html($heading); ?></span>
                    </h2>
                </div>
            </div>
            <div class="col-12 col-md-6 list">
                <!-- Livewhale Calendar Widget -->
                <?php if ( ! empty( $is_preview ) ) : // The LiveWhale widget script doesn't run in editor previews. ?>
                <p><em><?php echo esc_html( sprintf( __( 'LiveWhale calendar widget #%d displays here on the live page.' ), absint( $livewhale ) ) ); ?></em></p>
                <?php else : ?>
                <div class="lwcw" data-options="id=<?php echo absint($livewhale); ?>&format=html"></div>
                <script type="text/javascript" id="lw_lwcw" src="https://calendar.uams.edu/livewhale/theme/core/scripts/lwcw.js"></script>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>
<?php endif;