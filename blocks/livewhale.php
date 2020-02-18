<?php
/*
 *
 * UAMS Action Bar Block
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

// if ( $background_color == 'bg-white' || $background_color == 'bg-gray' ) {
//     $btn_color = 'primary';
// } else {
//     $btn_color = 'white';
// }

?>
<section class="uams-module livewhale<?php echo $className; ?> <?php //echo $background_color; ?>" id="<?php echo $livewhale; ?>" aria-label="<?php echo $heading; ?>">
    <h2 class="sr-only"><?php echo $heading; ?></h2>
    <div class="container-fluid">
        <div class="row">
            <!-- Livewhale Calendar Widget -->
            <div class="lwcw" data-options="id=<?php echo $livewhale; ?>&format=html"></div> 
            <script type="text/javascript" id="lw_lwcw" src="https://calendar.uams.edu/livewhale/theme/core/scripts/lwcw.js"></script>
        </div>
    </div>
</section>