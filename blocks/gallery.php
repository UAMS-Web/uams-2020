<?php
/*
 *
 * UAMS Gallery Block
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

$id = 'uams-gallery-' . $id;  
    
$className = '';
if( !empty($block['className']) ) {
    $className .= ' ' . $block['className'];
}
if( !empty($block['align']) ) {
    $className .= ' align' . $block['align'];
}
    

// Load values.
if ( empty($heading) )
    $heading = get_field('gallery_heading');
if ( empty($hide_heading) )
    $hide_heading = get_field('gallery_hide_heading');
if ( empty($description) )
    $description = get_field('gallery_description');
if ( empty($gallery_columns) )
    $gallery_columns = get_field('gallery_columns');
if ( empty($gallery_images) )
    $gallery_images = get_field('gallery_images');
if ( empty($gallery_crop) )
    $gallery_crop = get_field('gallery_crop');
if ( $gallery_crop == 'none' || $gallery_crop[0] == 'none' )
    $gallery_crop = -1;
if ( empty($background_color) )
    $background_color = get_field('gallery_background_color');
if ( empty($modal) )
    $modal = get_field('gallery_modal');
if ( empty($more) )
    $more = get_field('gallery_more');
if ( $more ) {
    if ( empty($more_text) )
        $more_text = get_field('gallery_more_text');
    if ( empty($more_button_text) )
        $more_button_text = get_field('gallery_more_button_text');
    if ( empty($more_button_url) )
        $more_button_url = get_field('gallery_more_button_url');
    if ( empty($more_button_target) ) 
        $more_button_target = $more_button_url['target'];
    if ( empty($more_button_description) )
        $more_button_description = get_field('gallery_more_button_description');
    if ( empty($more_button_color) && ( $background_color == 'bg-white' || $background_color == 'bg-gray' ) ) {
        $more_button_color = 'primary';
    } else {
        $more_button_color = 'white';
    }
}

if ($gallery_columns == '2') {
    $sm = 6;
    $md = $sm;
    $lg = 6;
} elseif ($gallery_columns == '3') {
    $sm = 6;
    $md = $sm;
    $lg = 4;
} elseif ($gallery_columns == '4') {
    $sm = 6;
    $md = $sm;
    $lg = 3;
} else { // $gallery_columns == '6'
    $sm = 6;
    $md = 4;
    $lg = 2;
} 


?>
<section class="uams-module gallery-block<?php echo $className; ?> <?php echo $background_color; ?>" id="<?php echo $id; ?>" aria-label="<?php echo $heading; ?>">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <h2 class="module-title<?php echo $hide_heading ? " sr-only" : ""; ?>">
                    <span class="title"><?php echo $heading; ?></span>
                </h2>
                <?php echo $description ? '<div class="module-body">'. $description .'</div>' : ''; ?>
            </div>
            <div class="col-12 image-container padded-grid">
                <div class="row">
                    <?php 
                        $i=0;
                        foreach($gallery_images as $gallery_image) {
                        // Load values.
                        $image_url = $gallery_image['url'];
                        $image_alt = $gallery_image['alt'];
                        $image_md_url = $gallery_image['sizes']['aspect-16-9'];
                        $image_id = $gallery_image['id'];
                        /* <img class="w-100" src="<?php echo esc_url($image_url); ?>" alt="<?php echo $image_alt; ?>"> */
                        ?>
                                <div class="col-12 col-sm-<?php echo $sm ?> col-md-<?php echo $md ?> col-lg-<?php echo $lg; ?>">
                                        <?php if ($modal) { ?>
                                            <a href="#" data-toggle="modal" data-target="#modal_<?php echo $i; ?>_<?php echo $id; ?>" aria-label="Show larger version of image <?php echo $i + 1; ?><?php echo $image_alt ? ': ' . $image_alt : ''; ?>">
                                        <?php } // endif ?>
                                        <picture>
                                            <?php if ( function_exists( 'fly_add_image_size' ) ) { ?>  
                                                <source srcset="<?php echo image_sizer($image_id, gallery_image_dimension('xxl', 12 / $lg, 1), gallery_image_dimension('xxl', 12 / $lg, 1, $gallery_crop), 'center', 'center'); ?>" 
                                                    media="(min-width: 1921px)">
                                                <source srcset="<?php echo image_sizer($image_id, gallery_image_dimension('xl', 12 / $lg, 1), gallery_image_dimension('xl', 12 / $lg, 1, $gallery_crop), 'center', 'center'); ?>" 
                                                    media="(min-width: 1500px)">
                                                <source srcset="<?php echo image_sizer($image_id, gallery_image_dimension('lg', 12 / $lg, 1), gallery_image_dimension('lg', 12 / $lg, 1, $gallery_crop), 'center', 'center'); ?>" 
                                                    media="(min-width: 1200px)">
                                                <source srcset="<?php echo image_sizer($image_id, gallery_image_dimension('md', 12 / $md, 1), gallery_image_dimension('md', 12 / $md, 1, $gallery_crop), 'center', 'center'); ?>" 
                                                    media="(min-width: 992px)">
                                                <source srcset="<?php echo image_sizer($image_id, gallery_image_dimension('sm', 12 / $sm, 1), gallery_image_dimension('sm', 12 / $sm, 1, $gallery_crop), 'center', 'center'); ?>" 
                                                    media="(min-width: 768px)">
                                                <source srcset="<?php echo image_sizer($image_id, gallery_image_dimension('xs', 1, 1), gallery_image_dimension('xs', 1, 1, $gallery_crop), 'center', 'center'); ?>" 
                                                    media="(min-width: 1px)">
                                                <!-- Fallback -->
                                                <img src="<?php echo image_sizer($image_id, gallery_image_dimension('xl', 2, 1), gallery_image_dimension('xl', 2, 1, $gallery_crop), 'center', 'center'); ?>" alt="<?php echo $image_alt; ?>" />
                                            <?php } else { ?>
                                                <!-- Fallback -->
                                                <img src="<?php echo wp_get_attachment_image_url( $image_id, 'aspect-16-9' ); ?>" alt="<?php echo $image_alt; ?>" />
                                            <?php } //endif ?>
                                        </picture>
                                    <?php if ($modal) { ?>
                                        </a>
                                    <?php } //endif ?>
                                </div>
                                <?php if ($modal) { ?>
                                    <div class="modal fade" id="modal_<?php echo $i; ?>_<?php echo $id; ?>" tabindex="-1" role="dialog" aria-label="Larger version of image <?php echo $i + 1; ?><?php echo $image_alt ? ': ' . $image_alt : ''; ?>" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered modal-xl" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                                <div class="modal-body">
                                                    <?php if ( function_exists( 'fly_add_image_size' ) ) { ?>  
                                                        <img src="<?php echo image_sizer($image_id, 1106, -1, 'center', 'center'); ?>" alt="<?php echo $image_alt; ?>" />
                                                    <?php } else { ?>
                                                        <img src="<?php echo wp_get_attachment_image_url( $image_id, 'content-image-full' ); ?>" alt="<?php echo $image_alt; ?>">
                                                    <?php } //endif ?>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                <?php } // endif ?>
                    <?php
                        $i++;
                    }
                    ?>
                </div>
            </div>
            <?php if ( $more ) { ?>
                <div class="col-12 more">
                    <p class="lead"><?php echo $more_text; ?></p>
                    <div class="cta-container">
                        <a href="<?php echo $more_button_url['url']; ?>" class="btn btn-<?php echo $more_button_color; ?>" aria-label="<?php echo $more_button_description; ?>"<?php $more_button_target ? ' target="'. $more_button_target . '"' : '' ?>><?php echo $more_button_text; ?></a>
                    </div>
                </div>
            <?php } // endif ?>
        </div>
    </div>
</section>