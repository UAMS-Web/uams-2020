<?php
/*
 *
 * UAMS Action Bar Block
 * 
 */

// Create id attribute allowing for custom "anchor" value.
if ( empty( $id ) )
    $id = 'action-bar-' . $block['id'];   

// Load values.
if ( empty($heading) )
    $heading = get_field('action_bar_heading');
if ( empty($background_color) )
    $background_color = get_field('action_bar_background_color');
if ( empty($action_bar_rows) )
    $action_bar_rows = get_field('action_bar_section');

if( $action_bar_rows ) {
    // $rows = get_field('action_bar_section');
    $row_count = count($action_bar_rows);
} 

?>
<section class="uams-module action-bar count-<?php echo $row_count < 4 ? "3" : "4"; ?> <?php echo $background_color; ?>" id="<?php echo $id; ?>">
    <h2 class="sr-only"><?php echo $heading; ?></h2>
    <div class="container-fluid">
        <div class="row">
<?php 
    foreach($action_bar_rows as $action_bar_row) {
    // Load values.
    $section_heading = $action_bar_row['action_bar_section_heading'];
    $body = $action_bar_row['action_bar_section_body'];
    $button_text = $action_bar_row['action_bar_section_button_text'];
    $button_url = $action_bar_row['action_bar_section_button_url'];
    $button_target = $action_bar_row['action_bar_section_button_target'];
    $button_desc = $action_bar_row['action_bar_section_button_description'];

?>
            <div class="col-12 <?php echo $row_count < 4 ? 'col-sm-4' : 'col-md-3'; ?>">
                <div class="inner-container">
                    <div class="text-container">
                        <h3 class="h5"><?php echo $section_heading; ?></h3>
                        <p><?php echo $body; ?></p>
                    </div>
                    <a class="btn" href="<?php echo $button_url; ?>" aria-label="<?php echo $button_desc; ?>"<?php echo $button_target ? ' target="_blank"' : ''; ?>><?php echo $button_text; ?></a>
                </div>
            </div>
    <?php
    }
    ?>
        </div>
    </div>
</section>