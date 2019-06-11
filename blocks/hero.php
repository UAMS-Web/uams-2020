<?php 
/*
 *
 * UAMS Hero Block
 * 
 */
// Create id attribute allowing for custom "anchor" value.
$id = 'hero-' . $block['id'];
if( !empty($block['anchor']) ) {
    $id = $block['anchor'];
}

// Create class attribute allowing for custom "className" and "align" values.
$className = 'uams-hero-block';
if( !empty($block['className']) ) {
    $className .= ' ' . $block['className'];
}
if( !empty($block['align']) ) {
    $className .= ' align' . $block['align'];
}

if( have_rows('hero') ): 
    $rows = get_field('hero');
    $row_count = count($rows);
?>
    <section class="hero carousel slide<?php echo $row_count > 1 ? " multiple-slides" : ""; ?>" id="carousel-<?php echo esc_attr($id); ?>">
<?php
        if($row_count > 1) { ?>
            <ol class="carousel-indicators">
                <?php for ($i = 0; $i < $row_count; $i++) { ?>
                <li data-target="#carousel-<?php echo esc_attr($id) ?>" data-slide-to="<?php echo $i; ?>" <?php echo (0 == $i ? 'class="active"' : ''); ?>></li>
                <?php } ?>
            </ol>
<?php   }  ?>
    <div class="carousel-inner" class="<?php echo esc_attr($className); ?>">
<?php 
    while ( have_rows('hero') ) : the_row(); 
// Load values and assing defaults.
    $heading = get_sub_field('hero_heading') ?: 'Heading goes here...';
    $body = get_sub_field('hero_body') ?: 'This is where the description goes';
    $button_text = get_sub_field('hero_button_text') ?: 'Learn More';
    $button_url = get_sub_field('hero_button_url') ?: '';
    $button_target = get_sub_field('hero_button_target') ?: '';
    $button_desc = get_sub_field('hero_button_description') ?: '';
    $image_desktop = get_sub_field('image_desktop') ?: '';
    $image_tablet = get_sub_field('image_tablet') ?: '';
    $image_mobile = get_sub_field('image_mobile') ?: '';
    $image_alt = get_sub_field('image_alt_text') ?: '';
    $background_color = get_sub_field('background_color')?: 'auto';

?>
                <div class="carousel-item <?php echo $background_color; ?><?php echo (0 == (get_row_index() - 1) ? ' active' : ''); ?>" id="carousel-item-<?php echo (get_row_index() - 1); ?>">
                        <div class="image-container">
                                <style>
                                        /* SM Breakpoint, retina */
                                        @media (min-width: 768px) {
                                                #carousel-item-<?php echo (get_row_index() - 1); ?> .image-container {
                                                        background-image: url("<?php echo wp_get_attachment_image_url( $image_tablet, 'full' ); ?>");
                                                }
                                        }
                                        /* MD Breakpoint, retina */
                                        @media (min-width: 992px) {
                                                #carousel-item-<?php echo (get_row_index() - 1); ?> .image-container {
                                                        background-image: url("<?php echo wp_get_attachment_image_url( $image_desktop, 'full' ); ?>");
                                                }
                                        }
                                </style>
                                <picture>
                                        <!-- Mobile, Aspect ratio 1.5:1 -->
                                        <source srcset="<?php echo wp_get_attachment_image_url( $image_mobile, 'full' ); ?>"
                                                        media="(min-width: 1px) and (-webkit-min-device-pixel-ratio: 2), (min-width: 1px) and (min-resolution: 192dpi)">
                                        <source srcset="<?php echo wp_get_attachment_image_url( $image_mobile, 'full' ); ?>"
                                                        media="(min-width: 1px)">
                                        <!-- Fallback, use Tablet, Aspect ratio 1.4132:1 -->
                                        <img src="<?php echo wp_get_attachment_image_url( $image_tablet, 'full' ); ?>" alt="<?php echo $image_alt; ?>" />
                                </picture>
                        </div>
                        <div class="text-container">
                                <div class="inner-container">
                                        <h2><?php echo $heading; ?></h2>
                                        <p><?php echo $body; ?></p>
                                        <a class="btn" href="<?php echo $button_url; ?>" aria-label="<?php echo $button_desc; ?>"<?php echo $button_target ? ' target="_blank"' : ''; ?>><?php echo $button_text; ?></a>
                                </div>
                        </div>
                </div>
<?php
        endwhile;
?>
            </div><!-- .carousel-inner -->
<?php
        if($row_count > 1) { ?>
            <a class="carousel-control-prev" href="#carousel-<?php echo esc_attr($id); ?>" role="button" data-slide="prev">
                <span class="fas fa-angle-left" aria-hidden="true"></span>
                <span class="sr-only">Previous</span>
            </a>
            <a class="carousel-control-next" href="#carousel-<?php echo esc_attr($id); ?>" role="button" data-slide="next">
                <span class="fas fa-angle-right" aria-hidden="true"></span>
                <span class="sr-only">Next</span>
            </a>
        <?php   }  ?>
  </section>
<?php
endif;
