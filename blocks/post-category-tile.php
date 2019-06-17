<?php 
/*
 *
 * UAMS Post Category Tile (Single)
 * 
 */

// Create id attribute allowing for custom "anchor" value.
$id = 'post-category-tile-' . $block['id'];

// Load values.
$heading = get_field('post-cat-tile_heading');
$hide_heading = get_field('post-cat-tile_hide-heading');
$background_color = get_field('post-cat-tile_background-color');

// The item's selected category.
$category = get_field('post-cat-tile_category');

$args = array(
    'post_type' => 'post',
    'post_status' => 'publish',
    'category_name' => 'markup',
    // I used "$category->slug" for the "category_name" value on the other module,
    // but I don't have the know-how to figure out why it won't work here.
    // So it's hardcoded to a category for now.
    'posts_per_page' => 1,
);

$arr_posts = new WP_Query( $args );

if ( $arr_posts->have_posts() ) : while ( $arr_posts->have_posts() ) : $arr_posts->the_post();

// Load values.
$image = get_post_thumbnail_id( $post->ID );
$post_button_text = get_field('post-cat-tile_post-button-text') ?: 'Read the Story';
$cat_button_text = get_field('post-cat-tile_category-button-text') ?: 'View ' . $category->name . ' Archive';

?>

<section class="uams-module post-category-tile <?php echo $background_color; ?>" id="<?php echo $id; ?>">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12<?php echo $hide_heading ? " sr-only" : ""; ?>">
                <h2 class="module-title"><span class="title"><?php echo $heading ?></span></h2>
            </div>
            <div class="col-12">
                <div class="inner-container">
                    <div class="image-container">
                        <picture>
                        <?php if ( function_exists( 'fly_add_image_size' ) ) { ?>      
                            <!-- 1:1 Aspect Ratio -->
                            <source 
                                media="(min-width: 1921px) and (-webkit-min-device-pixel-ratio: 2), 
                                (min-width: 1921px) and (min-resolution: 192dpi)"
                                srcset="<?php echo image_sizer($image, 992, 992, 'center', 'center'); ?>">
                            <source 
                                media="(min-width: 1921px)" 
                                srcset="<?php echo image_sizer($image, 496, 496, 'center', 'center'); ?>">
                            <source 
                                media="(min-width: 1500px) and (-webkit-min-device-pixel-ratio: 2), 
                                (min-width: 1500px) and (min-resolution: 192dpi)"
                                srcset="<?php echo image_sizer($image, 906, 906, 'center', 'center'); ?>">
                            <source 
                                media="(min-width: 1500px)" 
                                srcset="<?php echo image_sizer($image, 453, 453, 'center', 'center'); ?>">
                            <source 
                                media="(min-width: 1200px) and (-webkit-min-device-pixel-ratio: 2), 
                                (min-width: 1200px) and (min-resolution: 192dpi)"
                                srcset="<?php echo image_sizer($image, 696, 696, 'center', 'center'); ?>">
                            <source 
                                media="(min-width: 1200px)" 
                                srcset="<?php echo image_sizer($image, 348, 348, 'center', 'center'); ?>">
                            <source 
                                media="(min-width: 992px) and (-webkit-min-device-pixel-ratio: 2), 
                                (min-width: 992px) and (min-resolution: 192dpi)"
                                srcset="<?php echo image_sizer($image, 546, 546, 'center', 'center'); ?>">
                            <source 
                                media="(min-width: 992px)" 
                                srcset="<?php echo image_sizer($image, 273, 273, 'center', 'center'); ?>">
                            <source 
                                media="(min-width: 768px) and (-webkit-min-device-pixel-ratio: 2), 
                                (min-width: 768px) and (min-resolution: 192dpi)"
                                srcset="<?php echo image_sizer($image, 442, 442, 'center', 'center'); ?>">
                            <source 
                                media="(min-width: 768px)" 
                                srcset="<?php echo image_sizer($image, 221, 221, 'center', 'center'); ?>">
                            <source 
                                media="(min-width: 576px) and (-webkit-min-device-pixel-ratio: 2), 
                                (min-width: 576px) and (min-resolution: 192dpi)"
                                srcset="<?php echo image_sizer($image, 346, 346, 'center', 'center'); ?>">
                            <source 
                                media="(min-width: 576px)" 
                                srcset="<?php echo image_sizer($image, 173, 173, 'center', 'center'); ?>">
                            <source 
                                media="(min-width: 1px) and (-webkit-min-device-pixel-ratio: 2), 
                                (min-width: 1px) and (min-resolution: 192dpi)"
                                srcset="<?php echo image_sizer($image, 994, 994, 'center', 'center'); ?>">
                            <source 
                                media="(min-width: 1px)" 
                                srcset="<?php echo image_sizer($image, 497, 497, 'center', 'center'); ?>">
                            <?php } //endif ?>
                            <img src="<?php echo wp_get_attachment_image_url( $image, 'full' ); ?>" alt="Random image">
                        </picture>
                    </div>
                    <div class="text-container">
                        <h3><?php the_title(); ?></h3>
                        <p><?php $content = wp_strip_all_tags(get_the_content()); echo mb_strimwidth($content, 0, 176, '...');?></p>
                        <div class="cta-container">
                            <a href="<?php the_permalink(); ?>" class="btn btn-primary"><?php echo $post_button_text; ?></a>
                            <a href="#CATEGORY-URL" class="btn btn-outline-primary"><?php echo $cat_button_text; ?></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<?php endwhile; endif; ?>