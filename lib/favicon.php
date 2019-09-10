<?php
/**
 * Add favicons
 *
*/

function uamswp_add_favicon() { ?>
    <?php // generics ?>
    <link rel="icon" href="<?php echo get_stylesheet_directory_uri(); ?>/assets/favicon/favicon-32x32.png" sizes="32x32">
    <link rel="icon" href="<?php echo get_stylesheet_directory_uri(); ?>/assets/favicon/favicon-96x96.png" sizes="96x96">
    <link rel="icon" href="<?php echo get_stylesheet_directory_uri(); ?>/assets/favicon/favicon-128x128.png" sizes="128x128">
    <link rel="icon" href="<?php echo get_stylesheet_directory_uri(); ?>/assets/favicon/favicon-167x167.png" sizes="167x167">
    <link rel="icon" href="<?php echo get_stylesheet_directory_uri(); ?>/assets/favicon/favicon-192x192.png" sizes="192x192">
    <?php // iOS ?>
    <link rel="apple-touch-icon" href="<?php echo get_stylesheet_directory_uri(); ?>/assets/favicon/favicon-120x120.png" sizes="120x120">
    <link rel="apple-touch-icon" href="path/to/favicon-152x152.png" sizes="152x152">
    <link rel="apple-touch-icon" href="path/to/favicon-180x180.png" sizes="180x180">
    <?php // Windows 8 IE 10 ?>
    <meta name="msapplication-TileColor" content="#9D2235">
    <meta name="msapplication-TileImage" content="<?php echo get_stylesheet_directory_uri(); ?>/assets/favicon/favicon-144x144.png">
    <?php // Windows 8.1 + IE11 and above ?>
    <meta name="msapplication-config" content="<?php echo get_stylesheet_directory_uri(); ?>/assets/favicon/IEconfig.xml" />
<?php }
add_action('wp_head', 'uamswp_add_favicon');

