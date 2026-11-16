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
    header("Content-Security-Policy: frame-ancestors 'self' ".implode(' ', $ancestors).';');
}

/**
 * Builds the Content Security Policy the theme sends on every front-end page.
 *
 * The listed hosts were measured from a page load of the UAMS Search plugin's results with its Google Custom Search block
 * on (UAMS-Web/uams-2020#845): `cse.js` is served from `cse.google.com`, loads `cse_element__en.js` and the result
 * styles from `www.google.com`, calls `cse.google.com/cse/element/v1` and `clients1.google.com`, and shows result
 * thumbnails from `*.gstatic.com`. The rest are what the theme itself loads: Google Fonts, Tag Manager, the UAMS alert
 * from `www.uams.edu`, and Gravatar in the admin bar.
 *
 * `script-src` and `style-src` allow `'unsafe-inline'` because the theme and its plugins print inline scripts, style
 * elements and style attributes; the policy limits where scripts load from, not what a page prints inline.
 * `cse_element__en.js` calls `eval`, so a search request also gets `'unsafe-eval'` in `script-src`; no other request does,
 * because the Google block renders only on search results.
 * `UAMSWP_CSP_EXTRA_SOURCES` in wp-config.php lists further origins an install needs, and each is added to every
 * source directive that takes hosts.
 */
function uamswp_csp_policy(bool $search = false): string
{
    $extra = implode(' ', uamswp_framing_list('UAMSWP_CSP_EXTRA_SOURCES'));
    $sources = static fn (string $hosts): string => trim($hosts.' '.$extra);

    return implode('; ', [
        "default-src 'self'",
        'script-src '.$sources("'self' 'unsafe-inline'".($search ? " 'unsafe-eval'" : '').' https://cse.google.com https://www.google.com https://www.googletagmanager.com https://www.uams.edu'),
        'style-src '.$sources("'self' 'unsafe-inline' https://fonts.googleapis.com https://www.google.com"),
        'font-src '.$sources("'self' data: https://fonts.gstatic.com"),
        'img-src '.$sources("'self' data: https://www.google.com https://*.gstatic.com https://clients1.google.com https://secure.gravatar.com"),
        'connect-src '.$sources("'self' https://cse.google.com https://clients1.google.com https://www.uams.edu"),
        'frame-src '.$sources("'self' https://www.googletagmanager.com"),
        "object-src 'none'",
        "base-uri 'self'",
    ]);
}

/**
 * Whether the named constant, UAMSWP_CSP_ENFORCE in wp-config.php, enforces the policy: only a boolean `true` does, so a
 * string or a number set by mistake leaves it report-only.
 */
function uamswp_csp_enforced(string $constant): bool
{
    return defined($constant) && constant($constant) === true;
}

// The policy is sent as report-only unless the install sets UAMSWP_CSP_ENFORCE to true in wp-config.php, so a page that
// loads something the policy omits still works and the browser console names the omission.
add_action('send_headers', 'uamswp_send_csp_header');
/**
 * @param  mixed  $wp  The `WP` object, whose parsed query variables say whether this is a search request.
 */
function uamswp_send_csp_header($wp = null): void
{
    $search = is_object($wp) && isset($wp->query_vars) && is_array($wp->query_vars) && isset($wp->query_vars['s']);
    $name = uamswp_csp_enforced('UAMSWP_CSP_ENFORCE') ? 'Content-Security-Policy' : 'Content-Security-Policy-Report-Only';
    header($name.': '.uamswp_csp_policy($search).';');
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
