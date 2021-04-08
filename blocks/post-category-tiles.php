<?php 
/*
 *
 * UAMS Post Category Tiles (Double)
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
       
$id = 'stacked-image-text-' . $id; 

$className = '';
if( !empty($block['className']) ) {
    $className .= ' ' . $block['className'];
}
if( !empty($block['align']) ) {
    $className .= ' align' . $block['align'];
}

// Load values.
if ( empty( $heading ) )
    $heading = get_field('post_tiles_heading');
if ( empty( $hide_heading ) )
    $hide_heading = get_field('post_tiles_hide_heading');
if ( empty( $background_color ) )
    $background_color = get_field('post_tiles_background_color');
if ( empty($geo) )
    $geo = get_field('post_tiles_geo');
if ( empty($geo_region) )
    $geo_region = get_field('post_tiles_geo_region');

if ( empty( $post_tiles_rows ) )
    $post_tiles_rows = get_field('post_tiles_section');

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

if( $post_tiles_rows ) :
    $row_count = count($post_tiles_rows); 

?>
<section class="uams-module stacked-image-text post-category-tiles<?php echo $className; ?> <?php echo $background_color; ?>" id="<?php echo $id; ?>" aria-label="<?php echo $heading; ?>">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12<?php echo $hide_heading ? " sr-only" : ""; ?>">
                <h2 class="module-title"><span class="title"><?php echo $heading; ?></span></h2>
            </div>
            <?php 
                $index = 1;
                foreach ($post_tiles_rows as $post_tiles_row) {

                // The item's selected category.
                $category = $post_tiles_row['post_tiles_section_category'];

                $post_button_text = $post_tiles_row['post_tiles_section_post_button_text'] ?: 'Read the Story';
                $cat_button_text = $post_tiles_row['post_tiles_section_category_button_text'] ?: 'View ' . $category->name . ' Archive';

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
                $image = get_post_thumbnail_id( $arr_posts->ID );
                $alt_text = get_post_meta( $image, '_wp_attachment_image_alt', true );
            ?>
            <div class="col-12 col-sm-6 item">
                <div class="card">
                    <?php if ($image) { ?>
                    <div class="card-img-top">
                        <picture>
                            <?php if ( function_exists( 'fly_add_image_size' ) ) { ?>  
                            <!-- 16:9 Aspect Ratio -->
                            <source 
                                media="(min-width: 1500px)" 
                                srcset="<?php echo image_sizer($image, 556, 313, 'center', 'center'); ?> 1x, <?php echo image_sizer($image, 1112, 626, 'center', 'center'); ?> 2x">
                            <source 
                                media="(min-width: 1200px)" 
                                srcset="<?php echo image_sizer($image, 428, 241, 'center', 'center'); ?> 1x, <?php echo image_sizer($image, 856, 482, 'center', 'center'); ?> 2x">
                            <source 
                                media="(min-width: 992px)" 
                                srcset="<?php echo image_sizer($image, 328, 185, 'center', 'center'); ?> 1x, <?php echo image_sizer($image, 656, 369, 'center', 'center'); ?> 2x">
                            <source 
                                media="(min-width: 768px)" 
                                srcset="<?php echo image_sizer($image, 424, 239, 'center', 'center'); ?> 1x, <?php echo image_sizer($image, 848, 477, 'center', 'center'); ?> 2x">
                            <source 
                                media="(min-width: 576px)" 
                                srcset="<?php echo image_sizer($image, 631, 355, 'center', 'center'); ?> 1x, <?php echo image_sizer($image, 1262, 710, 'center', 'center'); ?> 2x">
                            <source 
                                media="(min-width: 1px)" 
                                srcset="<?php echo image_sizer($image, 512, 288, 'center', 'center'); ?> 1x, <?php echo image_sizer($image, 1024, 576, 'center', 'center'); ?> 2x">
                            <!-- Fallback -->
                            <img src="<?php echo image_sizer($image, 556, 313, 'center', 'center'); ?>" alt="" />
                            <?php } else { ?>
                            <!-- Fallback -->
                            <img src="<?php echo wp_get_attachment_image_url( $image, 'aspect-16-9' ); ?>" alt="" />
                            <?php } //endif ?>
                        </picture>
                    </div>
                    <?php } //endif $image ?>
                    <div class="card-body">
                        
                        <h3 class="card-title h5">
                            <span class="supertitle"><a href="<?php echo get_category_link( $category->term_id ); ?>" aria-label="Full list of <?php echo $category->name; ?> stories" data-moduletitle="<?php echo $heading; ?>" data-categorytitle="<?php echo $category->name; ?>"><?php echo $category->name; ?></a>:</span>
                            <?php the_title(); ?>
                        </h3>
                        <p class="card-text"><?php 
                        if ( ! has_excerpt() ) { 
                            $content = wp_trim_words(wp_strip_all_tags( get_the_content(), 50 )) ; 
                            $regex = "@(https?://([-\w\.]+[-\w])+(:\d+)?(/([\w/_\.#-]*(\?\S+)?[^\.\s])?)?)@";
                            echo mb_strimwidth(preg_replace($regex, ' ', $content), 0, 176, ' ...');
                            // echo wp_trim_words((preg_replace($regex, ' ', $content)), 25, ' ...'); // Option for words, instead of characters
                        } else { 
                            echo mb_strimwidth(get_the_excerpt(), 0, 176, ' ...'); 
                            // echo wp_trim_words(get_the_excerpt(), 25, ' ...');  // Words instead of character
                        }
                        ?></p>
                        <div class="cta-container">
                            <a class="btn btn-primary" href="<?php the_permalink(); ?>" aria-label="Read <?php the_title(); ?>" data-moduletitle="<?php echo $heading; ?>" data-categorytitle="<?php echo $category->name; ?>"><?php echo $post_button_text; ?></a>
                            <a class="btn btn-outline-primary" href="<?php echo get_category_link( $category->term_id ); ?>" aria-label="Full list of <?php echo $category->name; ?> stories" data-moduletitle="<?php echo $heading; ?>" data-categorytitle="<?php echo $category->name; ?>"><?php echo $cat_button_text; ?></a>
                        </div>
                        
                    </div>
                </div>
            </div>
            <?php endwhile; endif; ?>
            <?php
                    $index++;
                }
            ?>
        </div>
    </div>
</section>
<?php endif;
}