<?php
/*
 *
 * UAMS Action Bar Block
 * 
 */

// Create id attribute allowing for custom "anchor" value.
$id = 'action-bar-' . $block['id'];

// Load values.
$heading = get_field('action-bar_heading');
$background_color = get_field('action-bar_background-color');

if( have_rows('action-bar_section') ) {
    $rows = get_field('action-bar_section');
    $row_count = count($rows);
} 

?>
<section class="uams-module action-bar count-<?php echo $row_count < 4 ? "3" : "4"; ?> <?php echo $background_color; ?><?php echo $use_image ? ' bg-image' : ''; ?>" id="<?php echo $id; ?>">
    <h2 class="sr-only"><?php echo $heading; ?></h2>
    <div class="container-fluid">
        <div class="row">
<?php 
    while ( have_rows('action-bar_section') ) : the_row(); 
    // Load values.
    $section_heading = get_sub_field('action-bar_section_heading');
    $body = get_sub_field('action-bar_section_body');
    $button_text = get_sub_field('action-bar_section_button-text');
    $button_url = get_sub_field('action-bar_section_button-url');
    $button_target = get_sub_field('action-bar_section_button-target');
    $button_desc = get_sub_field('action-bar_section_button-description');

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
    endwhile;
?>
        </div>
    </div>
</section>