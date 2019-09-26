<?php 
/*
 *
 * UAMS Post Category Tile (Single)
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
    
$id = 'post-category-tile-' . $id;

$className = '';
if( !empty($block['className']) ) {
    $className .= ' ' . $block['className'];
}
if( !empty($block['align']) ) {
    $className .= ' align' . $block['align'];
}

// Load values.
if ( empty( $heading ) )
    $heading = get_field('post_tile_heading');
if ( empty( $hide_heading ) )
    $hide_heading = get_field('post_tile_hide_heading');
if ( empty( $background_color ) )
    $background_color = get_field('post_tile_background_color');

// The item's selected category.
if ( empty( $category ) )
    $category = get_field('post_tile_category');

$args = array(
    'post_type' => 'post',
    'post_status' => 'publish',
    'category_name' => $category->slug,
    'posts_per_page' => 1,
);

$arr_posts = new WP_Query( $args );

if ( $arr_posts->have_posts() ) : while ( $arr_posts->have_posts() ) : $arr_posts->the_post();

// Load values.
$image = get_post_thumbnail_id( $arr_posts->ID );
$alt_text = get_post_meta( $image, '_wp_attachment_image_alt', true );
if ( empty( $post_button_text ) )
    $post_button_text = get_field('post_tile_post_button_text') ?: 'Read the Story';
if ( empty( $cat_button_text ) )
    $cat_button_text = get_field('post_tile_category_button_text') ?: 'View ' . $category->name . ' Archive';

// if ( ! has_excerpt() ) { 
//     $content = wp_trim_words(wp_strip_all_tags( get_the_content(), 40 )) ; 
//     $regex = "@(https?://([-\w\.]+[-\w])+(:\d+)?(/([\w/_\.#-]*(\?\S+)?[^\.\s])?)?)@";
//     echo preg_replace($regex, ' ', $content);
// } else { 
//     $content = get_the_excerpt(); 
// } 

?>

<section class="uams-module post-category-tile<?php echo $className; ?> <?php echo $background_color; ?>" id="<?php echo $id; ?>" aria-label="<?php echo $heading ?>">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12<?php echo $hide_heading ? " sr-only" : ""; ?>">
                <h2 class="module-title"><span class="title"><?php echo $heading ?></span></h2>
            </div>
            <div class="col-12">                
                <div class="inner-container">
                <?php if ($image) { ?>
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
                            <!-- Fallback -->
                            <img src="<?php echo image_sizer($image, 496, 496, 'center', 'center'); ?>" alt="<?php echo $alt_text ? $alt_text : ''; ?>" />
                            <?php } else { ?>
                            <!-- Fallback -->
                            <img src="<?php echo wp_get_attachment_image_url( $image, 'aspect-16-9' ); ?>" alt="<?php echo $alt_text ? $alt_text : ''; ?>" />
                            <?php } //endif ?>
                        </picture>
                    </div>
                    <?php } //endif $image ?>
                    <div class="text-container">
                        <h3><?php the_title(); ?></h3>
                        <p><?php 
                         if ( ! has_excerpt() ) { 
                            $content = wp_trim_words(wp_strip_all_tags( get_the_content(), 50 )) ; 
                            $regex = "@(https?://([-\w\.]+[-\w])+(:\d+)?(/([\w/_\.#-]*(\?\S+)?[^\.\s])?)?)@";
                            echo mb_strimwidth(preg_replace($regex, ' ', $content), 0, 176, ' ...');
                            // echo wp_trim_words((preg_replace($regex, ' ', $content)), 25, ' ...'); // Option for words, instead of characters
                        } else { 
                            echo mb_strimwidth(get_the_excerpt(), 0, 176, ' ...'); 
                            // echo wp_trim_words(get_the_excerpt(), 25, ' ...');  // Words instead of character
                        }?></p>
                        <div class="cta-container">
                            <a href="<?php the_permalink(); ?>" class="btn btn-primary"><?php echo $post_button_text; ?></a>
                            <a href="<?php echo get_category_link( $category->term_id ); ?>" class="btn btn-outline-primary" aria-label="Full list of <?php echo $category->name; ?> stories"><?php echo $cat_button_text; ?></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<?php endwhile; endif; ?>