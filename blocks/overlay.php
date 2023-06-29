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
        if ( $overlay_row['overlay_section_button_url'] ) {
            $button_url = $overlay_row['overlay_section_button_url']['url'];
            $button_target = $overlay_row['overlay_section_button_url']['target'];
        }
        $button_desc = $overlay_row['overlay_section_button_description'];
        $background_color = $overlay_row['overlay_section_background_color'];
        $image = $overlay_row['overlay_section_image'];

?>
            <section class="col-12<?php echo $row_count > 1 ? " col-md-6" : ""; ?> item bg-image item-<?php echo $index; ?> <?php echo $background_color; ?>" aria-label="<?php echo $heading; ?>">
                <?php if ( $row_count > 1 && function_exists( 'fly_add_image_size' ) ) { // Background styles for two tiles in one row with Fly plugin ?>
                <style>
                    <?php
					// Image Width = Min-Width of Next Larger Breakpoint
					// Image Height = 75% of Image Width with minimum of 441px (Container has a 4:3 aspect ratio [1:0.75])
					echo '#' . $id; ?> .item-<?php echo $index; ?>:before {
                        background-image: url("<?php echo image_sizer($image, 576, 441, 'center', 'center'); ?>");
                    }

                    /* XXS Breakpoint, retina */
                    @media (-webkit-min-device-pixel-ratio: 2),
                    (min-resolution: 192dpi) {
                        <?php
						// Image width = Min-Width of Next Larger Breakpoint x 2 (High-Density Displays)
						// Image Height = 75% of Image Width with minimum of 882px (Container has a 4:3 aspect ratio [1:0.75])
						echo '#' . $id; ?> .item-<?php echo $index; ?>:before {
                            background-image: url("<?php echo image_sizer($image, 1152, 882, 'center', 'center'); ?>");
                        }
                    }

                    /* XS Breakpoint */
                    @media (min-width: 576px) {
                        <?php
						// Image Width = Min-Width of Next Larger Breakpoint
						// Image Height = 75% of Image Width with minimum of 441px (Container has a 4:3 aspect ratio [1:0.75])
						echo '#' . $id; ?> .item-<?php echo $index; ?>:before {
                            background-image: url("<?php echo image_sizer($image, 768, 576, 'center', 'center'); ?>");
                        }
                    }

                    /* XS Breakpoint, retina */
                    @media (min-width: 576px) and (-webkit-min-device-pixel-ratio: 2),
                    (min-width: 576px) and (min-resolution: 192dpi) {
                        <?php
						// Image width = Min-Width of Next Larger Breakpoint x 2 (High-Density Displays)
						// Image Height = 75% of Image Width with minimum of 882px (Container has a 4:3 aspect ratio [1:0.75])
						echo '#' . $id; ?> .item-<?php echo $index; ?>:before {
                            background-image: url("<?php echo image_sizer($image, 1536, 1152, 'center', 'center'); ?>");
                        }
                    }

                    /* SM Breakpoint */
                    @media (min-width: 768px) {
                        <?php
						// Image Width = Min-Width of Next Larger Breakpoint
						// Image Height = 64.5833% of Image Width with minimum of 496px (Container has a 8:3 aspect ratio [1:0.375] but is forced into a 48:31 aspect ratio [1:0.645833] at its tallest point due to the minimum height of 496px)
						echo '#' . $id; ?> .item-<?php echo $index; ?>:before {
                            background-image: url("<?php echo image_sizer($image, 992, 641, 'center', 'center'); ?>");
                        }
                    }

                    /* SM Breakpoint, retina */
                    @media (min-width: 768px) and (-webkit-min-device-pixel-ratio: 2),
                    (min-width: 768px) and (min-resolution: 192dpi) {
                        <?php
						// Image width = Min-Width of Next Larger Breakpoint x 2 (High-Density Displays)
						// Image Height = 64.5833% of Image Width with minimum of 992px (Container has a 8:3 aspect ratio [1:0.375] but is forced into a 48:31 aspect ratio [1:0.645833] at its tallest point due to the minimum height of 496px)
						echo '#' . $id; ?> .item-<?php echo $index; ?>:before {
                            background-image: url("<?php echo image_sizer($image, 1984, 1281, 'center', 'center'); ?>");
                        }
                    }

                    /* MD Breakpoint */
                    @media (min-width: 992px) {
                        <?php
						// Image width = 50% of Min-Width of Next Larger Breakpoint
						// Image Height = 100% of Image Width with minimum of 496px (Container has a 8:3 aspect ratio [1:0.375] but is forced into a 1:1 aspect ratio at its tallest point due to the minimum height of 496px)
						echo '#' . $id; ?> .item-<?php echo $index; ?>:before {
                            background-image: url("<?php echo image_sizer($image, 600, 600, 'center', 'center'); ?>");
                        }
                    }

                    /* MD Breakpoint, retina */
                    @media (min-width: 992px) and (-webkit-min-device-pixel-ratio: 2),
                    (min-width: 992px) and (min-resolution: 192dpi) {
                        <?php
						// Image width = 50% of Min-Width of Next Larger Breakpoint x 2 (High-Density Displays)
						// Image Height = 100% of Image Width with minimum of 496px (Container has a 8:3 aspect ratio [1:0.375] but is forced into a 1:1 aspect ratio at its tallest point due to the minimum height of 496px)
						echo '#' . $id; ?> .item-<?php echo $index; ?>:before {
                            background-image: url("<?php echo image_sizer($image, 1200, 1200, 'center', 'center'); ?>");
                        }
                    }

                    /* LG Breakpoint */
                    @media (min-width: 1200px) {
                        <?php
						// Image width = 50% of Min-Width of Next Larger Breakpoint
						// Image Height = 82.6666% of Image Width with minimum of 496px (Container has a 8:3 aspect ratio [1:0.375] but is forced into a 62:75 aspect ratio [1:0.826666] at its tallest point due to the minimum height of 496px)
						echo '#' . $id; ?> .item-<?php echo $index; ?>:before {
                            background-image: url("<?php echo image_sizer($image, 750, 620, 'center', 'center'); ?>");
                        }
                    }

                    /* LG Breakpoint, retina */
                    @media (min-width: 1200px) and (-webkit-min-device-pixel-ratio: 2),
                    (min-width: 1200px) and (min-resolution: 192dpi) {
                        <?php
						// Image width = 50% of Min-Width of Next Larger Breakpoint x 2 (High-Density Displays)
						// Image Height = 82.6666% of Image Width with minimum of 496px (Container has a 8:3 aspect ratio [1:0.375] but is forced into a 62:75 aspect ratio [1:0.826666] at its tallest point due to the minimum height of 496px)
						echo '#' . $id; ?> .item-<?php echo $index; ?>:before {
                            background-image: url("<?php echo image_sizer($image, 1500, 1240, 'center', 'center'); ?>");
                        }
                    }

                    /* XL Breakpoint */
                    @media (min-width: 1500px) {
                        <?php
						// Image width = 50% of Min-Width of Next Larger Breakpoint
						// Image height = 75% of Image Width with minimum of 496px (Container has 4:3 aspect ratio [1:0.75])
						echo '#' . $id; ?> .item-<?php echo $index; ?>:before {
                            background-image: url("<?php echo image_sizer($image, 961, 720, 'center', 'center'); ?>");
                        }
                    }

                    /* XL Breakpoint, retina */
                    @media (min-width: 1500px) and (-webkit-min-device-pixel-ratio: 2),
                    (min-width: 1500px) and (min-resolution: 192dpi) {
                        <?php
						// Image width = 50% of Min-Width of Next Larger Breakpoint x 2 (High-Density Displays)
						// Image height = 75% of Image Width with minimum of 992px (Container has 4:3 aspect ratio [1:0.75])
						echo '#' . $id; ?> .item-<?php echo $index; ?>:before {
                            background-image: url("<?php echo image_sizer($image, 1921, 1441, 'center', 'center'); ?>");
                        }
                    }

                    /* XXL Breakpoint */
                    @media (min-width: 1921px) {
                        <?php
						// Image width = 50% of 2560px
						// Image height = 75% of Image Width with minimum of 496px (Container has 4:3 aspect ratio [1:0.75])
						echo '#' . $id; ?> .item-<?php echo $index; ?>:before {
                            background-image: url("<?php echo image_sizer($image, 1280, 960, 'center', 'center'); ?>");
                        }
                    }

                    /* XXL Breakpoint, retina */
                    @media (min-width: 1921px) and (-webkit-min-device-pixel-ratio: 2),
                    (min-width: 1921px) and (min-resolution: 192dpi) {
                        <?php
						// Image width = 50% of 2560px x 2 (High-Density Displays)
						// Image height = 75% of Image Width with minimum of 992px (Container has 4:3 aspect ratio [1:0.75])
						echo '#' . $id; ?> .item-<?php echo $index; ?>:before {
                            background-image: url("<?php echo image_sizer($image, 2560, 1920, 'center', 'center'); ?>");
                        }
                    }
                </style>
                <?php } elseif ( function_exists( 'fly_add_image_size' ) ) { // Background styles for one tile in one row with Fly plugin ?>
                <style>
                    <?php
					// Image Width = Min-Width of Next Larger Breakpoint
					// Image Height = 75% of Image Width with minimum of 441px (Container has a 4:3 aspect ratio)
					echo '#' . $id; ?> .item-<?php echo $index; ?>:before {
                        background-image: url("<?php echo image_sizer($image, 576, 441, 'center', 'center'); ?>");
                    }

                    /* XXS Breakpoint, retina */
                    @media (-webkit-min-device-pixel-ratio: 2),
                    (min-resolution: 192dpi) {
                        <?php
						// Image width = Min-Width of Next Larger Breakpoint x 2 (High-Density Displays)
						// Image Height = 75% of Image Width with minimum of 882px (Container has a 4:3 aspect ratio)
						echo '#' . $id; ?> .item-<?php echo $index; ?>:before {
                            background-image: url("<?php echo image_sizer($image, 1152, 882, 'center', 'center'); ?>");
                        }
                    }

                    /* XS Breakpoint */
                    @media (min-width: 576px) {
                        <?php
						// Image Width = Min-Width of Next Larger Breakpoint
						// Image Height = 75% of Image Width with minimum of 441px (Container has a 4:3 aspect ratio)
						echo '#' . $id; ?> .item-<?php echo $index; ?>:before {
                            background-image: url("<?php echo image_sizer($image, 768, 576, 'center', 'center'); ?>");
                        }
                    }

                    /* XS Breakpoint, retina */
                    @media (min-width: 576px) and (-webkit-min-device-pixel-ratio: 2),
                    (min-width: 576px) and (min-resolution: 192dpi) {
                        <?php
						// Image width = Min-Width of Next Larger Breakpoint x 2 (High-Density Displays)
						// Image Height = 75% of Image Width with minimum of 882px (Container has a 4:3 aspect ratio)
						echo '#' . $id; ?> .item-<?php echo $index; ?>:before {
                            background-image: url("<?php echo image_sizer($image, 1536, 1152, 'center', 'center'); ?>");
                        }
                    }

                    /* SM Breakpoint */
                    @media (min-width: 768px) {
                        <?php
						// Image Width = Min-Width of Next Larger Breakpoint
						// Image Height = 64.5833% of Image Width with minimum of 496px (Container has a 8:3 aspect ratio [1:0.375] but is forced into a 48:31 aspect ratio [1:0.645833] at its tallest point due to the minimum height of 496px)
						echo '#' . $id; ?> .item-<?php echo $index; ?>:before {
                            background-image: url("<?php echo image_sizer($image, 992, 641, 'center', 'center'); ?>");
                        }
                    }

                    /* SM Breakpoint, retina */
                    @media (min-width: 768px) and (-webkit-min-device-pixel-ratio: 2),
                    (min-width: 768px) and (min-resolution: 192dpi) {
                        <?php
						// Image width = Min-Width of Next Larger Breakpoint x 2 (High-Density Displays)
						// Image Height = 64.5833% of Image Width with minimum of 992px (Container has a 8:3 aspect ratio [1:0.375] but is forced into a 48:31 aspect ratio [1:0.645833] at its tallest point due to the minimum height of 496px)
						echo '#' . $id; ?> .item-<?php echo $index; ?>:before {
                            background-image: url("<?php echo image_sizer($image, 1984, 1281, 'center', 'center'); ?>");
                        }
                    }

                    /* MD Breakpoint */
                    @media (min-width: 992px) {
                        <?php
						// Image Width = Min-Width of Next Larger Breakpoint
						// Image Height = 50% of Image Width with minimum of 496px (Container has a 8:3 aspect ratio [1:0.375] but is forced into a 2:1 aspect ratio [1:0.5] at its tallest point due to the minimum height of 496px)
						echo '#' . $id; ?> .item-<?php echo $index; ?>:before {
                            background-image: url("<?php echo image_sizer($image, 1200, 600, 'center', 'center'); ?>");
                        }
                    }

                    /* MD Breakpoint, retina */
                    @media (min-width: 992px) and (-webkit-min-device-pixel-ratio: 2),
                    (min-width: 992px) and (min-resolution: 192dpi) {
                        <?php
						// Image Width = Min-Width of Next Larger Breakpoint x 2 (High-Density Displays)
						// Image Height = 50% of Image Width with minimum of 992px (Container has a 8:3 aspect ratio [1:0.375] but is forced into a 2:1 aspect ratio [1:0.5] at its tallest point due to the minimum height of 496px)
						echo '#' . $id; ?> .item-<?php echo $index; ?>:before {
                            background-image: url("<?php echo image_sizer($image, 2400, 1200, 'center', 'center'); ?>");
                        }
                    }

                    /* LG Breakpoint */
                    @media (min-width: 1200px) {
                        <?php
						// Image Width = Min-Width of Next Larger Breakpoint
						// Image Height = 41.3333% of Image Width with minimum of 496px (Container has a 8:3 aspect ratio [1:0.375] but is forced into a 75:31 aspect ratio [1:0.413333] at its tallest point due to the minimum height of 496px)
						echo '#' . $id; ?> .item-<?php echo $index; ?>:before {
                            background-image: url("<?php echo image_sizer($image, 1500, 620, 'center', 'center'); ?>");
                        }
                    }

                    /* LG Breakpoint, retina */
                    @media (min-width: 1200px) and (-webkit-min-device-pixel-ratio: 2),
                    (min-width: 1200px) and (min-resolution: 192dpi) {
                        <?php
						// Image Width = Min-Width of Next Larger Breakpoint x 2 (High-Density Displays)
						// Image Height = 41.3333% of Image Width with minimum of 992px (Container has a 8:3 aspect ratio [1:0.375] but is forced into a 75:31 aspect ratio [1:0.413333] at its tallest point due to the minimum height of 496px)
						echo '#' . $id; ?> .item-<?php echo $index; ?>:before {
                            background-image: url("<?php echo image_sizer($image, 3000, 1240, 'center', 'center'); ?>");
                        }
                    }

                    /* XL Breakpoint */
                    @media (min-width: 1500px) {
                        <?php
						// Image Width = Min-Width of Next Larger Breakpoint
						// Image height = 37.5% of Image Width with minimum of 496px (Container has a 8:3 aspect ratio [1:0.375])
						echo '#' . $id; ?> .item-<?php echo $index; ?>:before {
                            background-image: url("<?php echo image_sizer($image, 1921, 720, 'center', 'center'); ?>");
                        }
                    }

                    /* XL Breakpoint, retina */
                    @media (min-width: 1500px) and (-webkit-min-device-pixel-ratio: 2),
                    (min-width: 1500px) and (min-resolution: 192dpi) {
                        <?php
						// Image Width = Min-Width of Next Larger Breakpoint x 2 (High-Density Displays)
						// Image height = 37.5% of Image Width with minimum of 992px (Container has a 8:3 aspect ratio [1:0.375])
						echo '#' . $id; ?> .item-<?php echo $index; ?>:before {
                            background-image: url("<?php echo image_sizer($image, 3842, 1441, 'center', 'center'); ?>");
                        }
                    }

                    /* XXL Breakpoint */
                    @media (min-width: 1921px) {
                        <?php
						// Image Width = 2560px
						// Image height = 37.5% of Image Width with minimum of 496px (Container has a 8:3 aspect ratio [1:0.375])
						echo '#' . $id; ?> .item-<?php echo $index; ?>:before {
                            background-image: url("<?php echo image_sizer($image, 2560, 960, 'center', 'center'); ?>");
                        }
                    }

                    /* XXL Breakpoint, retina */
                    @media (min-width: 1921px) and (-webkit-min-device-pixel-ratio: 2),
                    (min-width: 1921px) and (min-resolution: 192dpi) {
                        <?php
						// Image Width = 2560px x 2 (High-Density Displays)
						// Image height = 37.5% of Image Width with minimum of 992px (Container has a 8:3 aspect ratio [1:0.375])
						echo '#' . $id; ?> .item-<?php echo $index; ?>:before {
                            background-image: url("<?php echo image_sizer($image, 5120, 1920, 'center', 'center'); ?>");
                        }
                    }
                </style>
                <?php } else { // Background styles for no Fly plugin ?>
                <style>
                    <?php echo '#' . $id; ?> .item-<?php echo $index; ?>:before {
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