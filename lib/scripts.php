<?php

declare(strict_types=1);
/**
 * Scripts
 *
 * @since        1.0
 * @link         http://webdevsuperfast.github.io
 *
 * @author       Rotsen Mark Acob <webdevsuperfast.github.io>
 * @copyright    Copyright (c) 2015, Rotsen Mark Acob
 * @license      http://opensource.org/licenses/gpl-2.0.php GNU Public License
 */

// Theme Scripts & Stylesheet
add_action('wp_enqueue_scripts', 'uamswp_theme_scripts');
function uamswp_theme_scripts(): void
{
    $version = wp_get_theme()->get('Version');
    if (! is_admin()) {
        // Enqueue Bootstrap CSS
        wp_enqueue_style('app-css', UAMSWP_THEME_CSS.'app.css', [], uamswp_field_text(CHILD_THEME_VERSION), 'all');

        // Enqueue Google Fonts
        wp_enqueue_style('google-fonts', '//fonts.googleapis.com/css?family=Fira+Sans:300,300i,500,500i,600,600i,800,800i&display=swap', [], uamswp_field_text(CHILD_THEME_VERSION));

        // Disable the superfish script
        wp_deregister_script('superfish');
        wp_deregister_script('superfish-args');
        // Deregister Hover Intent
        wp_deregister_script('hoverIntent');

        // Deregister jQuery and use Bootstrap supplied version
        // wp_deregister_script( 'jquery' );
        // wp_register_script( 'jquery', UAMSWP_THEME_JS . 'jquery.min.js', array(), $version, false );
        // wp_enqueue_script( 'jquery' );

        // Register theme JS and enqueue it
        wp_register_script('app-js', UAMSWP_THEME_JS.'uams.min.js', ['jquery'], $version, true); // Renamed for dependencies
        wp_enqueue_script('app-js');

        // // Register Popper JS and enqueue it
        // wp_register_script( 'app-popper-js', UAMSWP_THEME_JS . 'popper.min.js', array( 'jquery' ), $version, true );
        // wp_enqueue_script( 'app-popper-js' );

        // // Register Bootstrap JS and enqueue it
        // wp_register_script( 'app-bootstrap-js', UAMSWP_THEME_JS . 'bootstrap.min.js', array( 'jquery' ), $version, true );
        // wp_enqueue_script( 'app-bootstrap-js' );

        // // Register Smart Menu JS and enqueue it
        // wp_register_script( 'app-smartmenu-js', UAMSWP_THEME_JS . 'jquery.smartmenus.min.js', array( 'jquery' ), $version, true );
        // wp_enqueue_script( 'app-smartmenu-js' );

        // // Register Smart Menu Boostrap Addon Js and enqueue it
        // wp_register_script( 'app-smartmenu-bootstrap-js', UAMSWP_THEME_JS . 'jquery.smartmenus.bootstrap-4.min.js', array( 'app-smartmenu-js' ), $version, true );
        // wp_enqueue_script( 'app-smartmenu-bootstrap-js' );

        // // Register Overflowing Navbar and enqueue it
        // wp_register_script( 'app-overflowing-navbar-js', UAMSWP_THEME_JS . 'overflowing-navbar.min.js', array( 'jquery' ), $version, true );
        // wp_enqueue_script( 'app-overflowing-navbar-js' );

        // Register Font Awesome JS and enqueue it
        // wp_register_script( 'app-fontawesome-js', 'https://use.fontawesome.com/releases/v5.6.3/js/all.js', array(), $version, true );
        // wp_enqueue_script( 'app-fontawesome-js' );

        // // Register Font Awesome 4 Shim and enqueue it
        // wp_register_script( 'app-fontawesome-shim-js', 'https://use.fontawesome.com/releases/v5.6.3/js/v4-shims.js', array( 'app-fontawesome-js', $version, true ) );
        // wp_enqueue_script( 'app-fontawesome-shim-js' );

        // // Register Search and Quick Links Trays JS and enqueue it
        // wp_register_script( 'app-headertrays-js', UAMSWP_THEME_JS . 'headertrays.min.js', array( 'jquery' ), $version, true );
        // wp_enqueue_script( 'app-headertrays-js' );

        // // Register theme JS and enqueue it
        // wp_register_script( 'app-js', UAMSWP_THEME_JS . 'app.min.js', array( 'jquery' ), $version, true );
        // wp_enqueue_script( 'app-js' );

        // // Register Font Awesome JS and enqueue it
        wp_register_script('fa-js', UAMSWP_THEME_JS.'fa.min.js', [], $version, true);
        wp_enqueue_script('fa-js');

        // Alert system based on uams-2016
        wp_enqueue_script('uams-alert', get_stylesheet_directory_uri().'/assets/js/uamsalert.js', [], '2.5.0', true);
        // wp_enqueue_style( 'uams-alert-style', get_stylesheet_directory_uri() . '/assets/css/uams.alert.css', array(), '1.0.0', 'all' );

        // Deregister shourtcode-ui styles
        wp_deregister_style('shortcodes_styles');

    }
}

// Editor Styles
add_action('init', 'uamswp_custom_editor_css');
function uamswp_custom_editor_css(): void
{
    add_editor_style(get_stylesheet_uri());
}

// CORS header fix
add_filter('allowed_http_origins', 'add_allowed_origins');

/**
 * @param  mixed  $origins
 * @return mixed[]
 */
function add_allowed_origins($origins): array
{
    $origins = uamswp_field_array($origins);
    $origins[] = 'https://www.uams.edu';
    $origins[] = 'https://public-api.wordpress.com';
    $origins[] = 'https://uamshealth.com';
    $origins[] = 'https://news.uams.edu';

    // Origins an install adds for itself: UAMSWP_EXTRA_ALLOWED_ORIGINS in wp-config.php. Unset adds none.
    foreach (uamswp_framing_list('UAMSWP_EXTRA_ALLOWED_ORIGINS') as $origin) {
        $origins[] = $origin;
    }

    return $origins;
}

/**
 * Reads a per-install list of origins from a constant defined in wp-config.php.
 *
 * An undefined or non-array constant yields an empty list, and so does every entry that is not a plain
 * http or https origin (scheme, host and optional port), so a value cannot add a second header
 * directive or a path.
 *
 * @return list<string>
 */
function uamswp_framing_list(string $constant): array
{
    $value = defined($constant) ? constant($constant) : [];
    $origins = [];
    foreach (is_array($value) ? $value : [] as $origin) {
        if (is_string($origin) && preg_match('#^https?://[A-Za-z0-9.-]+(:[0-9]+)?$#D', $origin) === 1) {
            $origins[] = $origin;
        }
    }

    return $origins;
}

// Framing policy for installs that are embedded by other UAMS sites. UAMSWP_FRAME_ANCESTORS in wp-config.php lists the
// origins allowed to frame the site; unset, the theme sends no framing headers.
add_action('send_headers', 'uamswp_send_frame_headers');
function uamswp_send_frame_headers(): void
{
    $ancestors = uamswp_framing_list('UAMSWP_FRAME_ANCESTORS');
    if ($ancestors === []) {
        return;
    }

    // Fallback for browsers that do not read frame-ancestors.
    header('X-Frame-Options: SAMEORIGIN');
    header("Content-Security-Policy: frame-ancestors 'self' ".implode(' ', $ancestors).';', false);
}

/**
 * The script policy: where a page may load scripts from.
 *
 * The hosts are the ones a measured page load of the search plugin's Google block needs (`cse.google.com` serves
 * `cse.js`, `www.google.com` serves the element script) plus the two other script hosts the same page loaded
 * (`www.googletagmanager.com` for the tag manager snippet in `functions.php`, `www.uams.edu` for the alert feed).
 * Inline scripts stay allowed because the theme prints the tag manager snippet inline. An install adds hosts for
 * itself through UAMSWP_SCRIPT_SRC_EXTRA in wp-config.php, for example the hosts its tag manager container loads.
 *
 * @return string The policy, as one header value.
 */
function uamswp_script_policy(): string
{
    $hosts = [
        'https://cse.google.com',
        'https://www.google.com',
        'https://www.googletagmanager.com',
        'https://www.uams.edu',
    ];
    foreach (uamswp_framing_list('UAMSWP_SCRIPT_SRC_EXTRA') as $origin) {
        if (! in_array($origin, $hosts, true)) {
            $hosts[] = $origin;
        }
    }

    return "script-src 'self' 'unsafe-inline' ".implode(' ', $hosts).';';
}

// The Google Custom Search script cannot carry Subresource Integrity, so a script policy is the control that limits
// where scripts load from. It is sent as `Content-Security-Policy-Report-Only` until an install sets
// UAMSWP_CSP_ENFORCE to true in wp-config.php, so a host the policy misses is reported in the console and never blocks
// a page. Both headers are added beside the framing policy rather than replacing it.
add_action('send_headers', 'uamswp_send_script_policy');
function uamswp_send_script_policy(): void
{
    $name = uamswp_script_policy_enforced() ? 'Content-Security-Policy' : 'Content-Security-Policy-Report-Only';
    header($name.': '.uamswp_script_policy(), false);
}

/**
 * Whether this install enforces the script policy rather than only reporting against it.
 *
 * @return bool True only when UAMSWP_CSP_ENFORCE is defined as the boolean true.
 */
function uamswp_script_policy_enforced(): bool
{
    return defined('UAMSWP_CSP_ENFORCE') && UAMSWP_CSP_ENFORCE === true;
}

// function defer_parsing_of_js( $url ) {
//     if ( is_user_logged_in() ) return $url; //don't break WP Admin
//     if ( FALSE === strpos( $url, '.js' ) ) return $url;
//     if ( strpos( $url, 'jquery' ) ) return $url; // Skip jquery file(s)
//     return str_replace( ' src', ' defer src', $url );
// }
// add_filter( 'script_loader_tag', 'defer_parsing_of_js', 10 );

// function add_rel_preload($html, $handle, $href, $media) {

//     if (is_admin())
//         return $html;

//      $html = <<<EOT
// <link rel='preload' as='style' onload="this.onload=null;this.rel='stylesheet'" id='$handle' href='$href' type='text/css' media='all' />
// EOT;
//     return $html;
// }
// add_filter( 'style_loader_tag', 'add_rel_preload', 10, 4 );

// function hook_css() {
//     include get_theme_file_path( '/assets/css/critical.css');
// }
// add_action('wp_head', 'hook_css');

/**
 * Gutenberg scripts and styles
 */
function uamswp_gutenberg_scripts(): void
{
    wp_enqueue_script('theme-editor', get_stylesheet_directory_uri().'/assets/js/editor.js', ['wp-blocks', 'wp-dom'], filemtime(get_stylesheet_directory().'/assets/js/editor.js'), true);
    // Loaded in the head (not footer) so the transform filter exists before ACF registers its blocks.
    wp_enqueue_script('uamswp-content-transform', get_stylesheet_directory_uri().'/assets/js/uams-content-transform.js', ['wp-hooks', 'wp-blocks'], filemtime(get_stylesheet_directory().'/assets/js/uams-content-transform.js'), false);
}

add_action('enqueue_block_editor_assets', 'uamswp_gutenberg_scripts');

/**
 * Editor canvas styles.
 * enqueue_block_assets loads inside the iframed editor canvas (WP 6.3+), unlike
 * enqueue_block_editor_assets, which only loads in the outer admin frame. The
 * is_admin() check keeps the font from double-loading on the front end.
 */
function uamswp_block_canvas_assets(): void
{
    if (is_admin()) {
        wp_enqueue_style('theme-fonts', uamswp_theme_fonts_url());
    }
}

add_action('enqueue_block_assets', 'uamswp_block_canvas_assets');

/**
 * Theme Fonts URL
 */
function uamswp_theme_fonts_url(): string
{
    return 'https://fonts.googleapis.com/css2?family=Fira+Sans&display=swap';
}

/**
 * Remove Gutenberg Block Directory
 */
add_action('admin_init', function (): void {
    remove_action('enqueue_block_editor_assets', 'wp_enqueue_editor_block_directory_assets');
});
/**
 * Remove Gutenberg Block Patterns
 */
remove_theme_support('core-block-patterns');

function uamswp_gutenberg_disable_all_colors_fonts(): void
{
    /**
     * Disable colors
     */
    add_theme_support('editor-color-palette', []);
    add_theme_support('disable-custom-colors');

    /**
     * Disable font sizes.
     */
    add_theme_support('editor-font-sizes', []);
    add_theme_support('disable-custom-font-sizes');
}

add_action('after_setup_theme', 'uamswp_gutenberg_disable_all_colors_fonts');
