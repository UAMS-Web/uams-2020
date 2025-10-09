<?php
/*
 *
 * UAMS Text & Image Overlay Block
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
    
$id = 'text-image-overlay-' . $id;
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
if ( empty($geo) )
    $geo = get_field('overlay_geo');
if ( empty($geo_region) )
    $geo_region = get_field('overlay_geo_region');

if( empty($overlay_rows) )
    $overlay_rows = get_field('overlay_section');

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

if( $overlay_rows ) :
    $row_count = count($overlay_rows);

?>
<div class="uams-module no-padding text-image-overlay<?php echo $className; ?>" id="<?php echo $id; ?>">
    <div class="container-fluid">
        <div class="row">
<?php 
    $index = 1;
    foreach($overlay_rows as $overlay_row) { 
        // Load values and adding defaults.
        $heading = $overlay_row['overlay_section_heading'];
        $body = $overlay_row['overlay_section_body'];
        $button_text = $overlay_row['overlay_section_button_text'];
        $button_url = ''; // Define variable
        $button_target = ''; // Define variable
        if ( $overlay_row['overlay_section_button_url'] ) {
            $button_url = $overlay_row['overlay_section_button_url']['url'];
            $button_target = $overlay_row['overlay_section_button_url']['target'];
        }
        $button_desc = $overlay_row['overlay_section_button_description'];
        $background_color = $overlay_row['overlay_section_background_color'];
        $image = $overlay_row['overlay_section_image'];

?>
            <section class="col-12<?php echo $row_count > 1 ? " col-sm-6" : ""; ?> item bg-image item-<?php echo $index; ?> <?php echo $background_color; ?>" aria-label="<?php echo $heading; ?>">
                <?php if ( $row_count > 1 && function_exists( 'bis_get_attachment_image' ) ) { // Background styles for two tiles in one row with BIS plugin ?>
                <style>
                    #<?php echo $id; ?> .item-<?php echo $index; ?>:before {
                        background-image: url("<?php echo image_sizer($image, 576, 432, 'center', 'center', 'aspect-4-3-small'); ?>");
                    }

                    /* XS Breakpoint */
                    @media (min-width: 576px) {
                        #<?php echo $id; ?> .item-<?php echo $index; ?>:before {
                            background-image: url("<?php echo image_sizer($image, 768, 576, 'center', 'center', 'aspect-4-3'); ?>");
                        }
                    }

                    /* SM Breakpoint */
                    @media (min-width: 768px) {
                        #<?php echo $id; ?> .item-<?php echo $index; ?>:before {
                            background-image: url("<?php echo image_sizer($image, 496, 372, 'center', 'center', 'aspect-4-3-small'); ?>");
                        }
                    }

                    /* MD Breakpoint */
                    @media (min-width: 992px) {
                        #<?php echo $id; ?> .item-<?php echo $index; ?>:before {
                            background-image: url("<?php echo image_sizer($image, 600, 450, 'center', 'center', 'aspect-4-3-samll'); ?>");
                        }
                    }

                    /* LG Breakpoint */
                    @media (min-width: 1200px) {
                        #<?php echo $id; ?> .item-<?php echo $index; ?>:before {
                            background-image: url("<?php echo image_sizer($image, 750, 563, 'center', 'center', 'aspect-4-3-samll'); ?>");
                        }
                    }

                    /* XL Breakpoint */
                    @media (min-width: 1500px) {
                        #<?php echo $id; ?> .item-<?php echo $index; ?>:before {
                            background-image: url("<?php echo image_sizer($image, 961, 720, 'center', 'center', 'aspect-4-3'); ?>");
                        }
                    }

                    /* XXL Breakpoint */
                    @media (min-width: 1921px) {
                        #<?php echo $id; ?> .item-<?php echo $index; ?>:before {
                            background-image: url("<?php echo image_sizer($image, 1280, 960, 'center', 'center', 'aspect-4-3'); ?>");
                        }
                    }
                </style>
                <?php } elseif ( function_exists( 'bis_get_attachment_image' ) ) { // Background styles for one tile in one row with BIS plugin ?>
                <style>
                    #<?php echo $id; ?> .item-<?php echo $index; ?>:before {
                        background-image: url("<?php echo image_sizer($image, 576, 432, 'center', 'center', 'aspect-4-3-small'); ?>");
                    }

                    /* XS Breakpoint */
                    @media (min-width: 576px) {
                        #<?php echo $id; ?> .item-<?php echo $index; ?>:before {
                            background-image: url("<?php echo image_sizer($image, 768, 576, 'center', 'center', 'aspect-4-3-small'); ?>");
                        }
                    }

                    /* SM Breakpoint */
                    @media (min-width: 768px) {
                        #<?php echo $id; ?> .item-<?php echo $index; ?>:before {
                            background-image: url("<?php echo image_sizer($image, 992, 744, 'center', 'center', 'aspect-4-3'); ?>");
                        }
                    }

                    /* MD Breakpoint */
                    @media (min-width: 992px) {
                        #<?php echo $id; ?> .item-<?php echo $index; ?>:before {
                            background-image: url("<?php echo image_sizer($image, 1200, 900, 'center', 'center', 'aspect-4-3'); ?>");
                        }
                    }

                    /* LG Breakpoint */
                    @media (min-width: 1200px) {
                        #<?php echo $id; ?> .item-<?php echo $index; ?>:before {
                            background-image: url("<?php echo image_sizer($image, 1500, 1125, 'center', 'center', 'aspect-4-3'); ?>");
                        }
                    }

                    /* XL Breakpoint */
                    @media (min-width: 1500px) {
                        #<?php echo $id; ?> .item-<?php echo $index; ?>:before {
                            background-image: url("<?php echo image_sizer($image, 1921, 1441, 'center', 'center', 'aspect-4-3'); ?>");
                        }
                    }

                    /* XXL Breakpoint */
                    @media (min-width: 1921px) {
                        #<?php echo $id; ?> .item-<?php echo $index; ?>:before {
                            background-image: url("<?php echo image_sizer($image, 2560, 1920, 'center', 'center', 'aspect-4-3'); ?>");
                        }
                    }
                </style>
                <?php } else { // Background styles for no BIS plugin ?>
                <style>
                    #<?php echo $id; ?> .item-<?php echo $index; ?>:before {
                            background-image: url("<?php echo wp_get_attachment_image_url( $image, 'aspect-4-3' ); ?>");
                    }
                </style>
                <?php } //endif ?>
                <div class="text-container">
                    <h2><?php echo $heading; ?></h2>
                    <p><?php echo $body; ?></p>
                    <a href="<?php echo $button_url; ?>" aria-label="<?php echo $button_desc; ?>" class="btn btn-white"<?php echo $button_target ? ' target="'. $button_target .'"' : ''; ?> data-itemtitle="<?php echo $heading; ?>"><?php echo $button_text; ?></a>
                </div>
            </section>
<?php
        $index++;
    }
?>
        </div>
    </div>
</div>
<?php endif;
}