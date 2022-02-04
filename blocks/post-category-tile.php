<?php 
/*
 *
 * UAMS Post Category Tile (Single)
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
    
$id = 'post-category-tile-' . $id;
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
if ( empty( $heading ) )
    $heading = get_field('post_tile_heading');
if ( empty( $hide_heading ) )
    $hide_heading = get_field('post_tile_hide_heading');
if ( empty( $background_color ) )
    $background_color = get_field('post_tile_background_color');
if ( empty($geo) )
    $geo = get_field('post_tile_geo');
if ( empty($geo_region) )
    $geo_region = get_field('post_tile_geo_region');

// The item's selected category.
if ( empty( $category ) )
    $category = get_field('post_tile_category');

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
                                media="(min-width: 1921px)" 
                                srcset="<?php echo image_sizer($image, 496, 496, 'center', 'center'); ?> 1x, <?php echo image_sizer($image, 992, 992, 'center', 'center'); ?> 2x">
                            <source 
                                media="(min-width: 1500px)" 
                                srcset="<?php echo image_sizer($image, 453, 453, 'center', 'center'); ?> 1x, <?php echo image_sizer($image, 906, 906, 'center', 'center'); ?> 2x">
                            <source 
                                media="(min-width: 1200px)" 
                                srcset="<?php echo image_sizer($image, 348, 348, 'center', 'center'); ?> 1x, <?php echo image_sizer($image, 696, 696, 'center', 'center'); ?> 2x">
                            <source 
                                media="(min-width: 992px)" 
                                srcset="<?php echo image_sizer($image, 273, 273, 'center', 'center'); ?> 1x, <?php echo image_sizer($image, 546, 546, 'center', 'center'); ?> 2x">
                            <source 
                                media="(min-width: 768px)" 
                                srcset="<?php echo image_sizer($image, 221, 221, 'center', 'center'); ?> 1x, <?php echo image_sizer($image, 442, 442, 'center', 'center'); ?> 2x">
                            <source 
                                media="(min-width: 576px)" 
                                srcset="<?php echo image_sizer($image, 173, 173, 'center', 'center'); ?> 1x, <?php echo image_sizer($image, 346, 346, 'center', 'center'); ?> 2x">
                            <source 
                                media="(min-width: 1px)" 
                                srcset="<?php echo image_sizer($image, 497, 497, 'center', 'center'); ?> 1x, <?php echo image_sizer($image, 994, 994, 'center', 'center'); ?> 2x">
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
                            <a href="<?php the_permalink(); ?>" class="btn btn-primary" aria-label="Read <?php the_title(); ?>" data-moduletitle="<?php echo $heading; ?>" data-categorytitle="<?php echo $category->name; ?>"><?php echo $post_button_text; ?></a>
                            <a href="<?php echo get_category_link( $category->term_id ); ?>" class="btn btn-outline-primary" aria-label="Full list of <?php echo $category->name; ?> stories" data-moduletitle="<?php echo $heading; ?>"  data-categorytitle="<?php echo $category->name; ?>"><?php echo $cat_button_text; ?></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<?php endwhile; endif; 
}?>