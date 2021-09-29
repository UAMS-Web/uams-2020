<?php
/*
 *
 * UAMS Action Bar Block
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

$id = 'action-bar-' . $id;
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
if ( empty($heading) )
    $heading = get_field('action_bar_heading');
if ( empty($background_color) )
    $background_color = get_field('action_bar_background_color');
if ( empty($action_bar_rows) )
    $action_bar_rows = get_field('action_bar_section');
if ( empty($geo) )
    $geo = get_field('action_bar_geo');
if ( empty($geo_region) )
    $geo_region = get_field('action_bar_geo_region');

if( $action_bar_rows ) {
    // $rows = get_field('action_bar_section');
    $row_count = count($action_bar_rows);
} 
if ( $background_color == 'bg-white' || $background_color == 'bg-gray' ) {
    $btn_color = 'primary';
} else {
    $btn_color = 'white';
}
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
<section class="uams-module action-bar<?php echo $className; ?> count-<?php echo $row_count < 4 ? "3" : "4"; ?> <?php echo $background_color; ?>" id="<?php echo $id; ?>" aria-label="<?php echo $heading; ?>">
    <h2 class="sr-only"><?php echo $heading; ?></h2>
    <div class="container-fluid">
        <div class="row">
<?php 
    foreach($action_bar_rows as $action_bar_row) {
    // Load values.
    $section_heading = $action_bar_row['action_bar_section_heading'];
    $body = $action_bar_row['action_bar_section_body'];
    $button_text = $action_bar_row['action_bar_section_button_text'];
    $button_url = $action_bar_row['action_bar_section_button_url']['url'];
    $button_target = $action_bar_row['action_bar_section_button_url']['target'];
    $button_desc = $action_bar_row['action_bar_section_button_description'];

?>
            <div class="col-12 <?php echo $row_count < 4 ? 'col-sm-4' : 'col-md-3'; ?> item">
                <div class="inner-container">
                    <div class="text-container">
                        <h3 class="h5" data-moduletitle="<?php echo $heading; ?>"><?php echo $section_heading; ?></h3>
                        <p><?php echo $body; ?></p>
                    </div>
                    <a class="btn btn-<?php echo $btn_color; ?>" href="<?php echo $button_url; ?>" aria-label="<?php echo $button_desc; ?>"<?php echo $button_target ? ' target="'. $button_target . '"' : ''; ?> data-moduletitle="<?php echo $heading; ?>" data-itemtitle="<?php echo $section_heading; ?>"><?php echo $button_text; ?></a>
                </div>
            </div>
    <?php
    }
    ?>
        </div>
    </div>
</section>
<?php endif;