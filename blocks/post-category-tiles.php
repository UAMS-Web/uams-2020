<?php 
/*
 *
 * UAMS Post Category Tiles (Double)
 * 
 */

// Create id attribute allowing for custom "anchor" value.
$id = 'stacked-image-text-' . $block['id'];

// Load values.
$heading = get_field('post-cat-tiles_heading');
$hide_heading = get_field('post-cat-tiles_hide-heading');
$background_color = get_field('post-cat-tiles_background-color');

if( have_rows('post-cat-tiles_section') ) {
    $rows = get_field('post-cat-tiles_section');
    $row_count = count($rows);
} 

?>
<section class="uams-module stacked-image-text <?php echo $background_color; ?>" id="<?php echo $id; ?>">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12<?php echo $hide_heading ? " sr-only" : ""; ?>">
                <h2 class="module-title"><span class="title"><?php echo $heading; ?></span></h2>
            </div>
            <?php 
                while ( have_rows('post-cat-tiles_section') ) : the_row(); 

                // The item's selected category.
                $category = get_sub_field('post-cat-tiles_section_category');

                $args = array(
                    'post_type' => 'post',
                    'post_status' => 'publish',
                    'category_name' => $category->slug,
                    'posts_per_page' => 1,
                );

                $arr_posts = new WP_Query( $args );

            ?>
            <?php 
                if ( $arr_posts->have_posts() ) : while ( $arr_posts->have_posts() ) : $arr_posts->the_post();

                // Load values.
                $image = get_post_thumbnail_id( $post->ID );
                $post_button_text = get_sub_field('post-cat-tiles_section_post-button-text') ?: 'Read the Story';
                $cat_button_text = get_sub_field('post-cat-tiles_section_category-button-text') ?: 'View ' . $category->name . ' Archive';
            ?>
            <div class="col-12 col-sm-6 item">
                <div class="card">
                    <div class="card-img-top">
                        <picture>
                            <?php if ( function_exists( 'fly_add_image_size' ) ) { ?>  
                            <!-- 16:9 Aspect Ratio -->
                            <source 
                                media="(min-width: 1500px) and (-webkit-min-device-pixel-ratio: 2), (min-width: 1500px) and (min-resolution: 192dpi)"
                                srcset="<?php echo image_sizer($image, 1112, 626, 'center', 'center'); ?>">
                            <source 
                                media="(min-width: 1500px)" 
                                srcset="<?php echo image_sizer($image, 556, 313, 'center', 'center'); ?>">
                            <source 
                                media="(min-width: 1200px) and (-webkit-min-device-pixel-ratio: 2), (min-width: 1200px) and (min-resolution: 192dpi)"
                                srcset="<?php echo image_sizer($image, 856, 482, 'center', 'center'); ?>">
                            <source 
                                media="(min-width: 1200px)" 
                                srcset="<?php echo image_sizer($image, 428, 241, 'center', 'center'); ?>">
                            <source 
                                media="(min-width: 992px) and (-webkit-min-device-pixel-ratio: 2), (min-width: 992px) and (min-resolution: 192dpi)"
                                srcset="<?php echo image_sizer($image, 656, 369, 'center', 'center'); ?>">
                            <source 
                                media="(min-width: 992px)" 
                                srcset="<?php echo image_sizer($image, 328, 185, 'center', 'center'); ?>">
                            <source 
                                media="(min-width: 768px) and (-webkit-min-device-pixel-ratio: 2), (min-width: 768px) and (min-resolution: 192dpi)"
                                srcset="<?php echo image_sizer($image, 848, 477, 'center', 'center'); ?>">
                            <source 
                                media="(min-width: 768px)" 
                                srcset="<?php echo image_sizer($image, 424, 239, 'center', 'center'); ?>">
                            <source 
                                media="(min-width: 576px) and (-webkit-min-device-pixel-ratio: 2), (min-width: 576px) and (min-resolution: 192dpi)"
                                srcset="<?php echo image_sizer($image, 1262, 710, 'center', 'center'); ?>">
                            <source 
                                media="(min-width: 576px)" 
                                srcset="<?php echo image_sizer($image, 631, 355, 'center', 'center'); ?>">
                            <source 
                                media="(min-width: 1px) and (-webkit-min-device-pixel-ratio: 2), (min-width: 1px) and (min-resolution: 192dpi)"
                                srcset="<?php echo image_sizer($image, 1024, 576, 'center', 'center'); ?>">
                            <source 
                                media="(min-width: 1px)" 
                                srcset="<?php echo image_sizer($image, 512, 288, 'center', 'center'); ?>">
                            <?php } //endif ?>
                            <!-- Fallback -->
                            <img src="<?php echo wp_get_attachment_image_url( $image, 'full' ); ?>" alt="Random image">
                        </picture>
                    </div>
                    <div class="card-body">
                        
                        <h3 class="card-title h5">
                            <span class="supertitle"><?php echo $category->name; ?>:</span>
                            <?php the_title(); ?>
                        </h3>
                        <p class="card-text"><strong><?php echo $postid; ?></strong> <?php $content = wp_strip_all_tags(get_the_content()); echo mb_strimwidth($content, 0, 176, '...');?></p>
                        <div class="cta-container">
                            <a class="btn btn-primary" href="<?php the_permalink(); ?>" aria-label="Read <?php the_title(); ?>"><?php echo $post_button_text; ?></a>
                            <a class="btn btn-outline-primary" href="<?php echo get_category_link( $category->term_id ); ?>" aria-label="Full list of <?php echo $category->name; ?> stories"><?php echo $cat_button_text; ?></a>
                        </div>
                        
                    </div>
                </div>
            </div>
            <?php endwhile; endif; ?>
            <?php
                endwhile;
            ?>
        </div>
    </div>
</section>