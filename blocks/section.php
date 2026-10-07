<?php
/*
 *
 * UAMS Section Block
 * 
 */
if (empty( $id )) {
	$id = '';
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

if ( empty($heading) )
    $heading = get_field('section_heading');
if ( empty($hide_heading) )
    $hide_heading = get_field('section_hide_heading');
if ( empty($background_color) )
    $background_color = get_field('section_background_color');
    
$className = ['uams-module', 'section-block', 'block-section', 'alignfull'];
if( !empty($block['className']) ) {
    $className = array_merge( $className, explode( ' ', $block['className'] ) );
}
$className = array_filter( array_unique( $className ) );

// NOTE: this allow-list only constrains the block editor UI (the InnerBlocks inserter).
// It is not enforced when content is saved or created over REST. See #575.
$allowed_blocks = uamswp_section_allowed_blocks();

$template = array(
);
?>  
<section class="<?php echo esc_attr( join( ' ', $className ) ); ?> <?php echo esc_attr( $background_color ); ?>" id="<?php echo esc_attr( $id ); ?>" aria-label="<?php echo esc_attr( $heading ); ?>">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12<?php echo $hide_heading ? " sr-only" : ""; ?>">
                <h2 class="module-title<?php echo $hide_heading ? " sr-only" : ""; ?>">
                    <span class="title"><?php echo esc_html( $heading ); ?></span>
                </h2>
            </div>
            <div class="module-body">
                <?php echo '<InnerBlocks allowedBlocks="' . esc_attr( wp_json_encode( $allowed_blocks ) ) . '" template="' . esc_attr( wp_json_encode( $template ) ) . '" />'; ?>
            </div>
        </div>
    </div>
</section>