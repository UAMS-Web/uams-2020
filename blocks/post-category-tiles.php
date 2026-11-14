<?php

/** @var int|false $i Module index, set by uamswp_module() when this template is included as a module; an ACF block always has $block and an id, so the index is not read. */
/*
 *
 * UAMS Post Category Tiles (Double)
 *
 */

$blockData = isset($block) ? uamswp_field_array($block) : [];
$moduleData = isset($module) ? uamswp_field_array($module) : [];

// Create id attribute allowing for custom "anchor" value.
if (empty($id)) {
    $id = '';
}
if (empty($id) && isset($block)) {
    $id = $blockData['id'] ?? null;
}
if (empty($id)) {
    $id = ! empty($moduleData['anchor_id']) ? sanitize_title_with_dashes(uamswp_field_text($moduleData['anchor_id'])) : 'module-'.((int) $i + 1);
}

$id = 'stacked-image-text-'.uamswp_field_text($id);
if (! empty($blockData['anchor'])) {
    $id = uamswp_field_text($blockData['anchor']);
}

$className = '';
if (! empty($blockData['className'])) {
    // @pest-mutate-ignore: ConcatEqualToEqual
    $className .= ' '.uamswp_field_text($blockData['className']);
}
if (! empty($blockData['align'])) {
    $className .= ' align'.uamswp_field_text($blockData['align']);
}

// Load values.
if (empty($heading)) {
    $heading = get_field('post_tiles_heading');
}
if (empty($hide_heading)) {
    $hide_heading = get_field('post_tiles_hide_heading');
}
if (empty($background_color)) {
    $background_color = get_field('post_tiles_background_color');
}
if (empty($geo)) {
    $geo = get_field('post_tiles_geo');
}
if (empty($geo_region)) {
    $geo_region = get_field('post_tiles_geo_region');
}

if (empty($post_tiles_rows)) {
    $post_tiles_rows = get_field('post_tiles_section');
}

$post_tiles_rows = uamswp_field_array($post_tiles_rows);
$heading = uamswp_field_text($heading);
$background_color = uamswp_field_text($background_color);

// GEO Logic
$geo_display = false;
$geo_regions = uamswp_field_array($geo_region);
if (! isset($geo) || $geo_regions === []) {
    $geo_display = true;
} else {
    if ($geo == 'include') {
        if (is_in_region($geo_regions)) {
            $geo_display = true;
        }
    } elseif ($geo == 'exclude') {
        if (is_not_in_region($geo_regions)) {
            $geo_display = true;
        }
    }
}
if (is_admin() && ! empty($geo) && $geo_regions !== []) {
    $geo_display = true;
    echo ucwords(uamswp_field_text($geo)).' region(s): '.implode(', ', array_map(uamswp_field_text(...), $geo_regions)).'<hr>';
}
if ($geo_display) {

    if ($post_tiles_rows !== []) {
        $row_count = count($post_tiles_rows);

        ?>
<section class="uams-module stacked-image-text post-category-tiles<?php echo $className; ?> <?php echo $background_color; ?>" id="<?php echo $id; ?>" aria-label="<?php echo $heading; ?>">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12<?php echo $hide_heading ? ' sr-only' : ''; ?>">
                <h2 class="module-title"><span class="title"><?php echo $heading; ?></span></h2>
            </div>
            <?php
                        // @pest-mutate-ignore: DecrementInteger,IncrementInteger
                        $index = 1;
        foreach ($post_tiles_rows as $post_tiles_row_value) {
            $post_tiles_row = uamswp_field_array($post_tiles_row_value);

            // The item's selected category, read as typed term fields.
            $category = $post_tiles_row['post_tiles_section_category'] ?? null;
            $category_data = is_object($category) ? get_object_vars($category) : [];
            $category_slug = uamswp_field_text($category_data['slug'] ?? null);
            $category_name = uamswp_field_text($category_data['name'] ?? null);
            $category_id = (int) uamswp_field_text($category_data['term_id'] ?? null);

            $post_button_text = uamswp_field_text($post_tiles_row['post_tiles_section_post_button_text'] ?? null) ?: 'Read the Story';
            $cat_button_text = uamswp_field_text($post_tiles_row['post_tiles_section_category_button_text'] ?? null) ?: 'View '.$category_name.' Archive';

            $args = [
                'post_type' => 'post',
                'post_status' => 'publish',
                'category_name' => $category_slug,
                'posts_per_page' => 1,
            ];

            $arr_posts = new WP_Query($args);

            ?>
            <?php
                if ($arr_posts->have_posts()) {
                    while ($arr_posts->have_posts()) {
                        $arr_posts->the_post();

                        // Load values.
                        $image = get_post_thumbnail_id();
                        $alt_text = get_post_meta((int) $image, '_wp_attachment_image_alt', true);
                        ?>
            <div class="col-12 col-sm-6 item">
                <div class="card">
                    <?php if ($image) { ?>
                    <div class="card-img-top">
                        <picture>
                            <?php if (function_exists('bis_get_attachment_image')) { ?>  
                            <!-- 16:9 Aspect Ratio -->
                            <source 
                                media="(min-width: 1500px)" 
                                srcset="<?php echo image_sizer($image, 556, 313, 'center', 'center', 'aspect-16-9-small'); ?>">
                            <source 
                                media="(min-width: 1200px)" 
                                srcset="<?php echo image_sizer($image, 428, 241, 'center', 'center', 'aspect-16-9-small'); ?>">
                            <source 
                                media="(min-width: 992px)" 
                                srcset="<?php echo image_sizer($image, 328, 185, 'center', 'center', 'aspect-16-9-small'); ?>">
                            <source 
                                media="(min-width: 768px)" 
                                srcset="<?php echo image_sizer($image, 424, 239, 'center', 'center', 'aspect-16-9-small'); ?>">
                            <source 
                                media="(min-width: 576px)" 
                                srcset="<?php echo image_sizer($image, 631, 355, 'center', 'center', 'aspect-16-9-small'); ?>">
                            <source 
                                media="(min-width: 1px)" 
                                srcset="<?php echo image_sizer($image, 512, 288, 'center', 'center', 'aspect-16-9-small'); ?>">
                            <!-- Fallback -->
                            <img src="<?php echo image_sizer($image, 556, 313, 'center', 'center', 'aspect-16-9-small'); ?>" alt="" />
                            <?php } else { ?>
                            <!-- Fallback -->
                            <img src="<?php echo wp_get_attachment_image_url($image, 'aspect-16-9'); ?>" alt="" />
                            <?php } // endif?>
                        </picture>
                    </div>
                    <?php } // endif $image?>
                    <div class="card-body">
                        
                        <h3 class="card-title h5">
                            <span class="supertitle"><a href="<?php echo get_category_link($category_id); ?>" aria-label="Full list of <?php echo $category_name; ?> stories" data-moduletitle="<?php echo $heading; ?>" data-categorytitle="<?php echo $category_name; ?>"><?php echo $category_name; ?></a>:</span>
                            <?php the_title(); ?>
                        </h3>
                        <p class="card-text"><?php
                                    if (! has_excerpt()) {
                                        $content = wp_trim_words(wp_strip_all_tags(get_the_content(), true));
                                        $regex = "@(https?://([-\w\.]+[-\w])+(:\d+)?(/([\w/_\.#-]*(\?\S+)?[^\.\s])?)?)@";
                                        echo mb_strimwidth((string) preg_replace($regex, ' ', $content), 0, 176, ' ...');
                                        // echo wp_trim_words((preg_replace($regex, ' ', $content)), 25, ' ...'); // Option for words, instead of characters
                                    } else {
                                        echo mb_strimwidth(get_the_excerpt(), 0, 176, ' ...');
                                        // echo wp_trim_words(get_the_excerpt(), 25, ' ...');  // Words instead of character
                                    }
                        ?></p>
                        <div class="cta-container">
                            <a class="btn btn-primary" href="<?php the_permalink(); ?>" aria-label="Read <?php the_title(); ?>" data-moduletitle="<?php echo $heading; ?>" data-categorytitle="<?php echo $category_name; ?>"><?php echo $post_button_text; ?></a>
                            <a class="btn btn-outline-primary" href="<?php echo get_category_link($category_id); ?>" aria-label="Full list of <?php echo $category_name; ?> stories" data-moduletitle="<?php echo $heading; ?>" data-categorytitle="<?php echo $category_name; ?>"><?php echo $cat_button_text; ?></a>
                        </div>
                        
                    </div>
                </div>
            </div>
            <?php }
                    } ?>
            <?php
                        // @pest-mutate-ignore: PostIncrementToPostDecrement
                        $index++;
        }
        ?>
        </div>
    </div>
</section>
<?php }
    }
