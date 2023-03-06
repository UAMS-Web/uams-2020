<?php
/*
 *
 * UAMS Section Block
 * 
 */
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
$id = 'uams-section-' . $id;
if( !empty($block['anchor']) ) {
    $id = $block['anchor'];
}

if ( empty($heading) && get_field('section_heading') ) {
    $heading = get_field('section_heading');
} elseif ( empty($heading) && get_sub_field('section_heading') ) {
    $heading = get_sub_field('section_heading');
}
if ( empty($hide_heading) && get_field('section_hide_heading') ) {
    $hide_heading = get_field('section_hide_heading');
} elseif ( empty($hide_heading) && get_sub_field('section_hide_heading') ) {
    $hide_heading = get_sub_field('section_hide_heading');
}
if ( empty($background_color) && get_field('section_background_color') ) {
    $background_color = get_field('section_background_color');
} elseif ( empty($background_color) && get_sub_field('section_background_color') ) {
    $background_color = get_sub_field('section_background_color');
}
$className = ['block-section', 'alignfull'];
if( !empty($block['className']) ) {
    $className = array_merge( $className, explode( ' ', $block['className'] ) );
}

$allowed_blocks = array( 'core/heading', 'core/paragraph', 'core/embed', 'core/list', 'core/quote', 'core/image', 'core/shortcode', 'core/table', 'core/file' );

$template = array(
);
?>  
<section class="uams-module section-block<?php echo join( ' ', $className ); ?> <?php echo $background_color; ?>" id="<?php echo $id; ?>" aria-label="<?php echo $heading; ?>">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12<?php echo $hide_heading ? " sr-only" : ""; ?>">
                <h2 class="module-title<?php echo $hide_heading ? " sr-only" : ""; ?>">
                    <span class="title"><?php echo $heading; ?></span>
                </h2>
            </div>
            <div class="module-body">
                <?php echo '<InnerBlocks allowedBlocks="' . esc_attr( wp_json_encode( $allowed_blocks ) ) . '" template="' . esc_attr( wp_json_encode( $template ) ) . '" />'; ?>
            </div>
        </div>
    </div>
</section>