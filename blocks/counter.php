<?php
/*
 *
 * UAMS Counter List Block
 * (Based on UAMS Link List Block)
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

$id = 'link-list-' .  $id;
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
    $heading = get_field('counter_list_heading');
if ( empty($hide_heading) ) 
    $hide_heading = get_field('counter_list_hide_heading');
if ( empty($description) ) 
    $description = get_field('counter_list_description');
if ( empty($start) ) 
    $start = get_field('counter_list_start');
if ( empty($start_custom) ) 
    $start_custom = get_field('counter_list_start_custom');
if ( empty($background_color) ) 
    $background_color = get_field('counter_list_background_color');
if ( empty($counter_list_rows) ) 
    $counter_list_rows = get_field('counter_list_section');

$row = 0;
?>
<section class="uams-module link-list counter-list link-list-layout-split<?php echo $className; ?><?php echo $block ? ' '. $background_color : ''; ?>" id="<?php echo $id; ?>" aria-label="<?php echo $heading; ?>">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12 col-md-6 heading">
                <div class="text-container">
                    <h2 class="module-title <?php echo $hide_heading ? " sr-only" : ""; ?>">
                        <span class="title"><?php echo $heading; ?></span>
                    </h2>
                    <?php echo $description ? '<p class="note">'. $description . '</p>' : ''; ?>
                </div>
            </div>
            <div class="col-12 col-md-6 list">
                    <ul>
                    <?php 
                        foreach($counter_list_rows as $counter_list_row) {
                        // Load values.
                        $row++;
                        $counter_item_title = $counter_list_row['counter_list_section_title'];
                        $counter_item_unit = $counter_list_row['counter_list_section_unit']; // Singular
                        $counter_item_units = $counter_list_row['counter_list_section_units']; // Plural
                        $counter_item_rate = $counter_list_row['counter_list_section_rate'];
                        $counter_item_start_inherit = $counter_list_row['counter_list_section_start_inherit'];
                        $counter_item_start_override = $counter_list_row['counter_list_section_start_override'];
                        $counter_item_start_custom = $counter_list_row['counter_list_section_start_custom'];
                        //
                        if ( $counter_item_start_inherit == 1 ) {
                            $counter_item_start = $start;

                            if ($counter_item_start == 'custom') {
                                $counter_item_start_custom = $start_custom;
                            }
                        } else {
                            $counter_item_start = $counter_item_start_override;

                            if ($counter_item_start == 'custom') {
                                $counter_item_start_custom = $counter_item_start_custom;
                            }
                        }
                        $date_day = date('Y-m-d') . ' 0:00:00';
                        $date_week = date('Y-m-d', strtotime('last sunday')) . ' 0:00:00';
                        $date_month = date('Y-m') . '-01 0:00:00';
                        $date_year = date('Y') . '-01-01 0:00:00';
                        $date_custom = $counter_item_start_custom;
                        //$date_user = date('Y-m-d H:i:s'); // Replace with user input date picker value

                        $date_now  = strtotime('now');
                        $date_string = ${'date_'.$counter_item_start};
                        $date = strtotime($date_string); // set the date value based on editor's selection
                        $date_diff = $date_now - $date;
                        $count_value = $counter_item_rate * $date_diff;
                        if ($date_diff <= 1) {
                            $append = $counter_item_unit;
                        } else {
                            $append = $counter_item_units;
                        }
                        if ($count_value < 1) {
                            $count_value_display = 'Less than 1';
                        } else {
                            $count_value_display = number_format(floor($count_value));
                        }

                    ?>
                        <li class="item" data-start-date="<?php echo $date_string; ?>" data-rate="<?php echo $counter_item_rate; ?>" data-unit-singular="<?php echo $counter_item_unit; ?>" data-unit-plural="<?php echo $counter_item_units; ?>" id="item-<?php echo $id . '-row-' . $row; ?>">
                            <div class="text-container">
                                <h3 class="h5"><?php echo $counter_item_title; ?></h3>
                                <p class="count"><span class="value"><?php echo $count_value_display; ?></span> <span class="append"><?php echo $append; ?></span></p>
                            </div>
                        </li>
                    <?php
                    }
                    ?>
                </ul>
            </div>
        </div>
    </div>
</section>
<script>
    (function($) {
        $(document).ready(function(){
            $('.counter-list .item').each(function() {
                var $id = $(this).attr('id');
                var $this = $('#' + $id);
                var $startDate = new Date($this.attr('data-start-date'));
                var $rate = $this.attr('data-rate');
                var $unit_singular = $this.attr('data-unit-singular');
                var $unit_plural = $this.attr('data-unit-plural');
                var $currentDate_js = new Date();
                var $dateDiff_js = ($currentDate_js - $startDate) / 1000; // seconds
                var $count = $dateDiff_js * $rate;

                function recalculateCount() {
                    var $currentDate_js = new Date();
                    var $dateDiff_js = ($currentDate_js - $startDate) / 1000; // seconds
                    var $count = $dateDiff_js * $rate;
                    if ($count < 1) {
                        var $count_display = '<1';
                        var $unit_display = $unit_singular;
                    } else {
                        var $count_display = Math.floor($dateDiff_js * $rate).toLocaleString('en');
                        var $unit_display = $unit_plural;
                    }
                    $('#' + $id + ' .count .value').text($count_display);
                    $('#' + $id + ' .count .append').text($unit_display);
                }

                recalculateCount();

                setInterval(function() {
                    recalculateCount();
                }, 1000);
            });
        })
    })(jQuery);
</script>

