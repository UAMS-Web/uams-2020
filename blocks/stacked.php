<?php
/*
 *
 * UAMS Stacked Image & Text Block
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

$id = 'stacked-image-text-' .  $id;
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
if ( empty($heading) && get_field('stacked_heading') ) {
    $heading = get_field('stacked_heading');
} elseif ( empty($heading) && get_sub_field('stacked_heading') ) {
    $heading = get_sub_field('stacked_heading');
}
if ( empty($hide_heading) && get_field('stacked_hide_heading') ) {
    $hide_heading = get_field('stacked_hide_heading');
} elseif ( empty($hide_heading) && get_sub_field('stacked_hide_heading') ) {
    $hide_heading = get_sub_field('stacked_hide_heading');
}
if ( empty($description) && get_field('stacked_description')) {
    $description = get_field('stacked_description');
} elseif ( empty($description) && get_sub_field('stacked_description')) {
    $description = get_sub_field('stacked_description');
}
if ( empty($background_color) && get_field('stacked_background_color') ) {
    $background_color = get_field('stacked_background_color');
} elseif ( empty($background_color) && get_sub_field('stacked_background_color') ) {
    $background_color = get_sub_field('stacked_background_color');
}
if ( empty($more) && get_field('stacked_more') ) {
    $more = get_field('stacked_more');
} elseif ( empty($more) && get_sub_field('stacked_more') ) {
    $more = get_sub_field('stacked_more');
}
if ( $more ) {
    if ( empty($more_text) && get_field('stacked_more_text') ) {
        $more_text = get_field('stacked_more_text');
    } elseif ( empty($more_text) && get_sub_field('stacked_more_text') ) {
        $more_text = get_sub_field('stacked_more_text');
    }
    if ( empty($more_button_text) && get_field('stacked_more_button_text') ) {
        $more_button_text = get_field('stacked_more_button_text');
    } elseif ( empty($more_button_text) && get_sub_field('stacked_more_button_text') ) {
        $more_button_text = get_sub_field('stacked_more_button_text');
    }
    if ( empty($more_button_url) && get_field('stacked_more_button_url') ) {
        $more_button_url = get_field('stacked_more_button_url');
    } elseif ( empty($more_button_url) && get_sub_field('stacked_more_button_url') ) {
        $more_button_url = get_sub_field('stacked_more_button_url');
    }
    if ( empty($more_button_target) ) {
        $more_button_target = $more_button_url['target'];
    }
    if ( empty($more_button_description) && get_field('stacked_more_button_description') ) {
        $more_button_description = get_field('stacked_more_button_description');
    } elseif ( empty($more_button_description) && get_sub_field('stacked_more_button_description') ) {
        $more_button_description = get_sub_field('stacked_more_button_description');
    }
    if ( empty($more_button_color) && ( $background_color == 'bg-white' || $background_color == 'bg-gray' || $background_color == 'bg-auto' ) ) {
        $more_button_color = 'primary';
    } else {
        $more_button_color = 'white';
    }
}
if ( empty($geo) )
    $geo = get_field('stacked_geo');
if ( empty($geo_region) )
    $geo_region = get_field('stacked_geo_region');
if ( empty($stacked_rows) && get_field('stacked_section') ) {
    $stacked_rows = get_field('stacked_section');
} elseif ( empty($stacked_rows) && get_sub_field('stacked_section') ) {
    $stacked_rows = get_sub_field('stacked_section');
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
if ($geo_display) {

if( $stacked_rows ) :
    $row_count = count($stacked_rows); // Not user, but just in case

?>
<section class="uams-module stacked-image-text<?php echo $className; ?> <?php echo $background_color; ?>" id="<?php echo $id; ?>" aria-label="<?php echo $heading; ?>">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12<?php echo ($hide_heading && empty($description)) ? " sr-only" : ""; ?>">
                <h2 class="module-title<?php echo ($hide_heading && $description) ? " sr-only" : ""; ?>">
                    <span class="title"><?php echo $heading; ?></span>
                </h2>
                <?php echo $description ? '<div class="module-description"><p>'. $description .'</p></div>' : ''; ?>
            </div>
            <div class="col-12">
                <div class="card-list card-list-left">
                    <?php 
                        foreach($stacked_rows as $stacked_row) {
                        // Load values.
                        $image = $stacked_row['stacked_section_image'];
                        $image_alt_native = get_post_meta($image, '_wp_attachment_image_alt', TRUE);
                        $image_alt_override = $stacked_row['stacked_section_alt_override'];
                        $item_heading = $stacked_row['stacked_section_heading'];
                        $body = $stacked_row['stacked_section_body'];
                        $button_text = $stacked_row['stacked_section_button_text'];
                        if ( $stacked_row['stacked_section_button_url'] ) {
                            $button_url = $stacked_row['stacked_section_button_url']['url'];
                            $button_target = $stacked_row['stacked_section_button_url']['target'];
                        }
                        $button_desc = $stacked_row['stacked_section_button_description'];

                    ?>
                        <div class="item">
                            <div class="card">
                                <div class="card-img-top">
                                    <picture>
                                        <?php if ( function_exists( 'fly_add_image_size' ) ) { ?>  
                                            <source srcset="<?php echo image_sizer($image, 455, 256, 'center', 'center'); ?> 1x, <?php echo image_sizer($image, 910, 512, 'center', 'center'); ?> 2x" 
                                                media="(min-width: 1921px)">
                                            <source srcset="<?php echo image_sizer($image, 433, 244, 'center', 'center'); ?> 1x, <?php echo image_sizer($image, 866, 487, 'center', 'center'); ?> 2x" 
                                                media="(min-width: 1500px)">
                                            <source srcset="<?php echo image_sizer($image, 455, 256, 'center', 'center'); ?> 1x, <?php echo image_sizer($image, 910, 512, 'center', 'center'); ?> 2x" 
                                                media="(min-width: 992px)">
                                            <source srcset="<?php echo image_sizer($image, 433, 244, 'center', 'center'); ?> 1x, <?php echo image_sizer($image, 866, 487, 'center', 'center'); ?> 2x" 
                                                media="(min-width: 768px)">
                                            <source srcset="<?php echo image_sizer($image, 455, 256, 'center', 'center'); ?> 1x, <?php echo image_sizer($image, 910, 512, 'center', 'center'); ?> 2x" 
                                                media="(min-width: 1px)">
                                            <!-- Fallback -->
                                            <img src="<?php echo image_sizer($image, 455, 256, 'center', 'center'); ?>" alt="<?php echo $image_alt_override ? $image_alt_override : $image_alt_native; ?>" />
                                        <?php } else { ?>
                                            <!-- Fallback -->
                                            <img src="<?php echo wp_get_attachment_image_url( $image, 'aspect-16-9' ); ?>" alt="<?php echo $image_alt_override ? $image_alt_override : $image_alt_native; ?>" />
                                        <?php } //endif ?>
                                    </picture>
                                </div>
                                <div class="card-body">
                                    <h3 class="card-title h5"><?php echo $item_heading; ?></h3>
                                    <p class="card-text"><?php echo $body; ?></p>
                                    <?php if ( $button_text ) { ?>  
                                        <a href="<?php echo $button_url; ?>" class="btn btn-primary stretched-link" aria-label="<?php echo $button_desc; ?>"<?php echo $button_target ? ' target="'. $button_target .'"' : ''; ?> data-moduletitle="<?php echo $heading; ?>" data-itemtitle="<?php echo $item_heading; ?>"><?php echo $button_text; ?></a>
                                    <?php } //endif ?>
                                </div>
                            </div>
                        </div>
                    <?php } // end foreach ?>
                </div>
            </div>
            <?php if ( $more ) { ?>
                <div class="col-12 more">
                    <p class="lead"><?php echo $more_text; ?></p>
                    <div class="cta-container">
                        <a href="<?php echo $more_button_url['url']; ?>" class="btn btn-outline-<?php echo $more_button_color; ?>" aria-label="<?php echo $more_button_description; ?>"<?php $more_button_target ? ' target="'. $more_button_target . '"' : '' ?>><?php echo $more_button_text; ?></a>
                    </div>
                </div>
            <?php } // endif ?>
        </div>
    </div>
</section>
<?php endif;
}