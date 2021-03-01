<?php
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
if( !empty($block['anchor']) ) {
    $id = $block['anchor'];
}

$id = 'content-' . $id;  
    
$className = '';
if( !empty($block['className']) ) {
    $className .= ' ' . $block['className'];
}
if( !empty($block['align']) ) {
    $className .= ' align' . $block['align'];
}  

// Load values.
if ( empty($heading) )
    $heading = get_field('innersection_heading');
if ( empty($hide_heading) )
    $hide_heading = get_field('innersection_hide_heading');
if ( empty($background_color) )
    $background_color = get_field('innersection_background_color');
// $classes = ['block-icon-heading'];
// if( !empty( $block['className'] ) )
//     $classes = array_merge( $classes, explode( ' ', $block['className'] ) );
if( !empty( $block['align'] ) )
    $classes[] = 'align' . $block['align'];

$anchor = '';
if( !empty( $block['anchor'] ) )
	$anchor = ' id="' . sanitize_title( $block['anchor'] ) . '"';

$allowed_blocks = array( 'core/heading', 'core/paragraph', 'core/embed' );

$template = array(
	// array('core/paragraph', array(
    //     'placeholder' => 'Content Goes Here',
	// )),
);
?>
<section class="uams-module content-block<?php echo $className; ?> <?php echo $background_color; ?>" id="<?php echo esc_attr($id); ?>" aria-label="<?php echo $heading; ?>">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <h2 class="module-title <?php echo $hide_heading ? " sr-only" : ""; ?>">
                    <span class="title"><?php echo $heading; ?></span>
                </h2>
<?php echo '<div class="module-body">';
	echo '<InnerBlocks allowedBlocks="' . esc_attr( wp_json_encode( $allowed_blocks ) ) . '" template="' . esc_attr( wp_json_encode( $template ) ) . '" />';
echo '</div>'; ?>
            </div>
        </div>
    </div>
</section>