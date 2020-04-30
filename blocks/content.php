<?php
/*
 *
 * UAMS Content Block
 * 
 */

// Create id attribute allowing for custom "anchor" value.
$id = '';
if ( empty( $id ) && isset($block) ) {
    $id = $block['id'];
} 
if ( empty ($id) ) {
    $id = !empty( $module['anchor_id'] ) ? sanitize_title_with_dashes( $module['anchor_id'] ) : 'module-' . ( $i + 1 );
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
    $heading = get_field('content_heading');
if ( empty($heading) )
    $hide_heading = get_field('content_hide_heading');
if ( empty($content_block) )
    $content_block = get_field('content_content');
if ( empty($background_color) )
    $background_color = get_field('content_background_color');

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