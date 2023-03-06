<?php
/*
 *
 * UAMS Link List Block
 * 
 */

// Create id attribute allowing for custom "anchor" value.
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
if ( empty($heading) && get_field('link_list_heading') ) {
    $heading = get_field('link_list_heading');
} elseif ( empty($heading) && get_sub_field('link_list_heading') ) {
    $heading = get_sub_field('link_list_heading');
}
if ( empty($description) && get_field('link_list_description') ) {
    $description = get_field('link_list_description');
} elseif ( empty($description) && get_sub_field('link_list_description') ) {
    $description = get_sub_field('link_list_description');
}
if ( empty($background_color) && get_field('link_list_background_color') ) {
    $background_color = get_field('link_list_background_color');
} elseif ( empty($background_color) && get_sub_field('link_list_background_color') ) {
    $background_color = get_sub_field('link_list_background_color');
}
if ( empty($link_list_rows) && get_field('link_list_section') ) {
    $link_list_rows = get_field('link_list_section');
} elseif ( empty($link_list_rows) && get_sub_field('link_list_section') ) {
    $link_list_rows = get_sub_field('link_list_section');
}
if ( empty($geo) )
    $geo = get_field('link_list_geo');
if ( empty($geo_region) )
    $geo_region = get_field('link_list_geo_region');

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
<section class="uams-module link-list link-list-layout-split<?php echo $className; ?> <?php echo $background_color; ?>" id="<?php echo $id; ?>" aria-label="<?php echo $heading; ?>">

    <div class="container-fluid">
        <div class="row">
            <div class="col-12 col-md-6 heading">
                <div class="text-container">
                    <h2 class="module-title">
                        <span class="title"><?php echo $heading; ?></span>
                    </h2>
                    <?php echo $description ? '<p class="note">'. $description . '</p>' : ''; ?>
                </div>
            </div>
            <div class="col-12 col-md-6 list">
                    <ul>
                    <?php 
                    if ($link_list_rows):
                        foreach($link_list_rows as $link_list_row) {
                        // Load values.
                        $link_title = $link_list_row['link_list_section_title'];
                        $body = $link_list_row['link_list_section_body'];
                        if ($link_list_row['link_list_section_url']){
                            $link_url = $link_list_row['link_list_section_url']['url'];
                            $link_target = $link_list_row['link_list_section_url']['target'];
                        }
                        $link_desc = $link_list_row['link_list_section_description'];
                    ?>
                        <li class="item">
                            <div class="text-container">
                                <h3 class="h5"><a class="stretched-link" href="<?php echo $link_url; ?>"<?php echo $link_target ? ' target="'. $link_target . '"' : ''; ?> aria-label="<?php echo $link_desc; ?>" data-moduletitle="<?php echo $heading; ?>"><?php echo $link_title; ?></a></h3>
                                <?php echo $body ? '<p>'. $body . '</p>' : ''; ?>
                            </div>
                        </li>
                    <?php
                        }
                    endif;
                    ?>
                </ul>
            </div>
        </div>
    </div>
    <div class="container-fluid">
        <div class="row">
        </div>
    </div>
</section>
<?php endif;