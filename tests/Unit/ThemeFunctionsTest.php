<?php

declare(strict_types=1);

use Brain\Monkey\Functions;
use Uams2020\Tests\Support\OptionString;

use function Brain\Monkey\Filters\expectAdded;
use function Brain\Monkey\Functions\when;

it('matches a prefix without treating it as a pattern', function ($value, $prefix, $expected): void {
    expect(startsWith($value, $prefix))->toBe($expected);
})->with([
    ['uams-health', 'uams', true], ['uams-health', 'health', false],
    ['abc', '', true], ['', '', true], ['', 'a', false], ['a', 'ab', false],
    ['UAMS', 'uams', false], ['a.b', 'a.', true],
]);

it('formats telephone numbers while pinning short and unsupported lengths', function ($phone, $us, $dash): void {
    expect(format_phone_us($phone))->toBe($us)
        ->and(format_phone_dash($phone))->toBe($dash);
})->with([
    ['', '', ''], ['123', '', ''], ['(12)', '12', '12'],
    ['5551234', '555-1234', '555-1234'],
    ['501-555-1234', '(501) 555-1234', '501-555-1234'],
    ['1 (501) 555-1234', '(501) 555-1234', '501-555-1234'],
    ['501555123412345', '(501) 555-1234 Ext. 12345', '501-555-1234,12345'],
    ['1501555123412345', '(501) 555-1234 Ext. 12345', '501-555-1234,12345'],
    ['12345678', '12345678', '12345678'],
    ['letters', '', ''],
]);

it('dispatches supported telephone styles and preserves an unknown style', function (): void {
    expect(format_phone('us', '5015551234'))->toBe('(501) 555-1234')
        ->and(format_phone('dash', '5015551234'))->toBe('501-555-1234')
        ->and(format_phone('unknown', '+44 20 1234'))->toBe('+44 20 1234');
});

it('calculates gallery dimensions at every breakpoint', function ($breakpoint, $expected): void {
    expect(gallery_image_dimension($breakpoint, 2))->toBe($expected)
        ->and(gallery_image_dimension($breakpoint, 2, 2))->toBe($expected * 2)
        ->and(gallery_image_dimension($breakpoint, 2, 1, 2))->toBe($expected / 2)
        ->and(gallery_image_dimension($breakpoint, 2, 2, -1))->toBe(-1);
})->with([
    ['xxs', 337], ['xs', 337], ['sm', 433], ['md', 537],
    ['lg', 687], ['xl', 897.5], ['xxl', 1217], ['unknown', 1217],
]);

it('pins gallery padding, spacing, density limits and loose breakpoint matching', function (): void {
    expect(gallery_image_dimension('sm', 1))->toBe(896)
        ->and(gallery_image_dimension('md', 3))->toBe(348)
        ->and(gallery_image_dimension('xs', 2, 3))->toBe(674)
        ->and(gallery_image_dimension('xs', 2, 0))->toBe(337);
});

it('keeps rate limits per client and expires the boundary timestamp', function (): void {
    $now = 1000;
    when('time')->alias(static function () use (&$now): int {
        return $now;
    });
    $limiter = new SlidingWindowRateLimiter(2, 10);

    expect($limiter->isRequestAllowed('alice'))->toBeTrue()
        ->and($limiter->isRequestAllowed('alice'))->toBeTrue()
        ->and($limiter->isRequestAllowed('alice'))->toBeFalse()
        ->and($limiter->isRequestAllowed('bob'))->toBeTrue();
    $now = 1009;
    expect($limiter->isRequestAllowed('alice'))->toBeFalse();
    $now = 1010;
    expect($limiter->isRequestAllowed('alice'))->toBeTrue()
        ->and($limiter->isRequestAllowed('alice'))->toBeTrue()
        ->and($limiter->isRequestAllowed('alice'))->toBeFalse();
});

it('rejects requests when the rate limit is zero', function (): void {
    expect((new SlidingWindowRateLimiter(0, 10))->isRequestAllowed('alice'))->toBeFalse();
});

it('preserves other header attributes while appending the current aria label', function (): void {
    expect(uamswp_add_aria(['class' => 'header']))->toBe(['class' => 'header', 'aria-label' => 'Site Header'])
        ->and(uamswp_add_aria(['aria-label' => 'Existing ']))->toBe(['aria-label' => 'Existing Site Header'])
        ->and(uamswp_add_aria(['aria-label' => '']))->toBe(['aria-label' => 'Site Header']);
});

it('adds translated image size labels without losing existing sizes', function (): void {
    when('__')->returnArg();
    expect(uams_custom_add_image_size_names(['thumbnail' => 'Thumbnail']))->toBe([
        'thumbnail' => 'Thumbnail',
        'content-image-side' => 'Content image aligned left/right',
        'content-image-center' => 'Content image aligned center',
        'content-image-wide' => 'Content image aligned wide',
        'content-image-full' => 'Content image aligned full',
    ]);
});

it('chooses the site title and home URL from site identity', function ($site, $subsite, $title, $home): void {
    when('uams_get_site_info')->justReturn(['site' => $site, 'subsite' => $subsite, 'department' => '']);
    when('get_bloginfo')->justReturn('Department title');
    expect(uams_site_title())->toBe($title)->and(uams_get_home_link())->toBe($home);
})->with([
    ['uamshealth', 'main', 'UAMS Health', 'https://uamshealth.com'],
    ['uamshealth', new OptionString('main'), 'UAMS Health', 'https://uamshealth.com'],
    ['uamshealth', 'clinic', 'Department title', 'https://uamshealth.com'],
    ['inside', 'main', 'Department title', 'https://inside.uams.edu'],
    ['uams', 'main', 'Department title', 'https://www.uams.edu'],
    ['institute', 'eye', 'Department title', 'https://www.uams.edu'],
]);

it('replaces page crumbs only on the front page', function (bool $front): void {
    when('is_front_page')->justReturn($front);
    when('uams_site_title')->justReturn('Department');

    expect(uams_breadcrumb_home('Original'))->toBe($front ? 'Department' : 'Original');
})->with([true, false]);

it('uses an external link only for posts with link format', function ($input, $external, $format, $expected): void {
    when('get_the_id')->justReturn(17);
    when('get_post')->justReturn((object) ['ID' => 42]);

    $id = empty($input) ? 17 : 42;
    Functions\expect('get_post_meta')->once()->with($id, 'post_custom_link', true)->andReturn($external);
    Functions\expect('get_post_format')->once()->with($id)->andReturn($format);
    when('get_permalink')->justReturn('https://example.test/internal/');
    expect(uams_get_permalink($input))->toBe($expected);
})->with([
    [null, 'https://example.test/external/', 'link', 'https://example.test/external/'],
    [0, '', 'link', 'https://example.test/internal/'],
    [42, 'https://example.test/external/', 'standard', 'https://example.test/internal/'],
    [(object) ['ID' => 42], '', false, 'https://example.test/internal/'],
]);

it('adds a module class only to ordinary widgets in the footer', function ($params, $expected): void {
    expect(footer_widgets($params))->toBe($expected);
})->with([
    [[], []],
    [[['id' => 'sidebar', 'before_widget' => '<div class="widget">']], [['id' => 'sidebar', 'before_widget' => '<div class="widget">']]],
    [[['id' => 'footer-1', 'before_widget' => '<div class="widget">']], [['id' => 'footer-1', 'before_widget' => '<div class="uams-module widget">']]],
    [[['id' => 'footer-1', 'before_widget' => '<div class="uamswp-widget">']], [['id' => 'footer-1', 'before_widget' => '<div class="uamswp-widget">']]],
    [[['id' => 'footer-1', 'before_widget' => 'uamswp<div class="widget">']], [['id' => 'footer-1', 'before_widget' => 'uamswp<div class="widget">']]],
]);

it('pins the current footer treatment of a truthy non-string widget ID', function (): void {
    expect(footer_widgets([['id' => true, 'before_widget' => '<div class="widget">']]))->toBe([['id' => true, 'before_widget' => '<div class="uams-module widget">']]);
});

it('leaves unrelated REST arguments alone when filters are missing or not arrays', function ($filter): void {
    expect(rest_api_filter_add_filter_param(['post_type' => 'post'], ['filter' => $filter]))->toBe(['post_type' => 'post']);
})->with([[null], [false], [''], ['posts_per_page=10'], [5], [[]]]);

it('bounds the REST page size without admitting unsafe query fields', function ($size, $expected): void {
    $args = ['post_type' => 'post', 'posts_per_page' => 7];
    $filter = [
        'posts_per_page' => $size, 'orderby' => 'date', 'order' => 'DESC',
        'offset' => 0, 'category_name' => 'news', 'tag' => 'research', 'author_name' => 'editor',
        'meta_query' => [['key' => 'secret']], 'meta_key' => 'secret', 'meta_value' => 'x',
        'meta_compare' => 'EXISTS', 'post_type' => 'private', 'unknown' => 'x',
    ];
    expect(rest_api_filter_add_filter_param($args, ['filter' => $filter]))->toBe([
        'post_type' => 'post', 'posts_per_page' => $expected, 'orderby' => 'date', 'order' => 'DESC',
        'offset' => 0, 'category_name' => 'news', 'tag' => 'research', 'author_name' => 'editor',
    ]);
})->with([[0, 7], [1, 1], [100, 100], [101, 7], [-1, 7], ['25', '25'], ['invalid', 7]]);

it('retains REST arguments when a safe field is explicitly null', function (): void {
    expect(rest_api_filter_add_filter_param(['tag' => 'old'], ['filter' => ['tag' => null]]))->toBe(['tag' => 'old']);
});

it('registers REST filters only for post types exposed in REST', function (): void {
    Functions\expect('get_post_types')->once()->with(['show_in_rest' => true], 'objects')->andReturn([(object) ['name' => 'post'], (object) ['name' => 'page']]);
    expectAdded('rest_post_query')->once()->with('rest_api_filter_add_filter_param', 10, 2);
    expectAdded('rest_page_query')->once()->with('rest_api_filter_add_filter_param', 10, 2);
    rest_api_filter_add_filters();
});

it('collects multisite IDs and returns the original query', function (): void {
    Functions\expect('get_sites')->once()->with(['number' => 2000])->andReturn([(object) ['blog_id' => 8], (object) ['blog_id' => 3]]);
    $query = Mockery::mock();
    $query->shouldReceive('set')->once()->with('searchblogs', '8,3');
    expect(rlv_search_all_blogs($query))->toBe($query);
});

it('handles an empty multisite list', function (): void {
    when('get_sites')->justReturn([]);
    $query = Mockery::mock();
    $query->shouldReceive('set')->once()->with('searchblogs', '');
    expect(rlv_search_all_blogs($query))->toBe($query);
});

it('validates GTM IDs without replacing an earlier validation failure', function ($valid, $value, $expected): void {
    expect(uamswp_validate_gtm_id($valid, $value))->toBe($expected);
})->with([
    [true, '', true], [true, 'GTM-ABC123', true],
    [true, 'gtm-ABC', 'Enter a valid Google Tag Manager container ID (GTM-XXXXXXX).'],
    [true, 'GTM-', 'Enter a valid Google Tag Manager container ID (GTM-XXXXXXX).'],
    [true, 'GTM-A<script>', 'Enter a valid Google Tag Manager container ID (GTM-XXXXXXX).'],
    [true, 'GTM-A B', 'Enter a valid Google Tag Manager container ID (GTM-XXXXXXX).'],
    ['Earlier error', 'GTM-A', 'Earlier error'], [false, 'GTM-A', false],
]);

it('disables SEOPress breadcrumb inline styles', function (): void {
    expect(sp_pro_breadcrumbs_css())->toBeFalse();
});

it('renders only the skip links supported by the current layout', function ($layout, bool $primary, $footer, bool $activeFooter, array $targets): void {
    when('genesis_a11y')->justReturn(true);
    Functions\expect('genesis_skiplinks_markup')->once();
    when('genesis_nav_menu_supported')->justReturn($primary);
    when('has_nav_menu')->justReturn($primary);
    when('genesis_site_layout')->justReturn($layout);
    when('current_theme_supports')->justReturn($footer !== false);
    when('get_theme_support')->justReturn($footer);
    when('is_active_sidebar')->justReturn($activeFooter);
    when('esc_html__')->returnArg();
    when('esc_url')->returnArg();
    ob_start();
    uamswp_skip_links();
    $html = ob_get_clean();
    $labels = [
        'genesis-nav-primary' => 'Skip to primary navigation', 'genesis-content' => 'Skip to main content',
        'genesis-sidebar-primary' => 'Skip to primary sidebar', 'genesis-sidebar-secondary' => 'Skip to secondary sidebar',
        'genesis-footer-widgets' => 'Skip to footer',
    ];
    $expected = '<nav aria-label="Skip links"><ul class="genesis-skip-link">';
    foreach ($targets as $target) {
        $expected .= '<li><a href="#'.$target.'" class="screen-reader-shortcut"> '.$labels[$target].'</a></li>';
    }

    expect($html)->toBe($expected.'</ul></nav>');
})->with([
    ['full-width-content', false, false, false, ['genesis-content']],
    ['content-sidebar', true, [1], true, ['genesis-nav-primary', 'genesis-content', 'genesis-sidebar-primary', 'genesis-footer-widgets']],
    ['sidebar-sidebar-content', false, [], true, ['genesis-content', 'genesis-sidebar-primary', 'genesis-sidebar-secondary']],
    ['sidebar-content-sidebar', false, ['invalid'], true, ['genesis-content', 'genesis-sidebar-primary', 'genesis-sidebar-secondary']],
    ['content-sidebar-sidebar', false, [1], false, ['genesis-content', 'genesis-sidebar-primary', 'genesis-sidebar-secondary']],
]);

it('emits no skip links when accessibility support is disabled', function (): void {
    Functions\expect('genesis_a11y')->once()->with('skip-links')->andReturn(false);
    Functions\expect('genesis_skiplinks_markup')->never();
    ob_start();
    uamswp_skip_links();
    expect(ob_get_clean())->toBe('');
});

it('prints the configured GTM container or the current fallback', function ($container, $disabled, $expected): void {
    when('get_option')->alias(static fn ($key) => $key === 'options_google_tag_manager_id' ? $container : $disabled);
    when('esc_js')->returnArg();
    when('esc_url')->returnArg();
    ob_start();
    uamswp_gtm_1();
    $head = ob_get_clean();
    ob_start();
    uamswp_gtm_2();
    $body = ob_get_clean();
    if ($disabled === '1') {
        expect($head)->toBe('<!-- Google Tag Manager is disabled -->')->and($body)->toBe('');
    } else {
        expect($head)->toContain("})(window,document,'script','dataLayer','".$expected."');")
            ->and($body)->toContain('src="https://www.googletagmanager.com/ns.html?id='.$expected.'"', 'height="0" width="0" aria-hidden="true"');
    }
})->with([['GTM-ABC123', '', 'GTM-ABC123'], ['', false, 'GTM-NGG4P7F'], ['GTM-ABC123', '1', ''], ['GTM-ABC123', 1, 'GTM-ABC123']]);

it('includes the reset key and an encoded login in the custom password message', function (): void {
    when('get_option')->justReturn('Site &amp; Name');
    Functions\expect('wp_specialchars_decode')->once()->with('Site &amp; Name', ENT_QUOTES)->andReturn('Site & Name');
    when('__')->returnArg();
    Functions\expect('network_site_url')->once()->with('/uams-login/?action=rp&key=reset-key&login=editor%2Bname', 'login')->andReturn('https://example.test/uams-login/?action=rp&key=reset-key&login=editor%2Bname');
    expect(my_retrieve_password_message('old', 'reset-key', 'editor+name', null))->toBe(
        "Someone has requested a password reset for the following account:\r\n\r\n"
        ."Site Name: Site & Name\r\n\r\n"
        ."Username: editor+name\r\n\r\n"
        ."If this was a mistake, just ignore this email and nothing will happen.\r\n\r\n"
        ."To reset your password, visit the following address:\r\n\r\n"
        ."<https://example.test/uams-login/?action=rp&key=reset-key&login=editor%2Bname>\r\n"
    );
});

it('registers and enqueues the admin stylesheet', function (): void {
    when('get_stylesheet_directory_uri')->justReturn('https://example.test/theme');
    Functions\expect('wp_register_style')->once()->with('admin-css', 'https://example.test/theme/assets/css/admin.css', false, '1.0.0');
    Functions\expect('wp_enqueue_style')->once()->with('admin-css');
    enqueue_admin_style_sheet();
});

// A predeclared uams_get_site_info() may supply stringable site identifiers.
it('accepts site identity values from a compatible external helper', function (string $site, string $title, string $home): void {
    when('uams_get_site_info')->justReturn(['site' => new OptionString($site), 'subsite' => 'main', 'department' => 'main']);
    when('get_bloginfo')->justReturn('Department title');
    expect(uams_site_title())->toBe($title)->and(uams_get_home_link())->toBe($home);
})->with([
    ['uamshealth', 'UAMS Health', 'https://uamshealth.com'],
    ['inside', 'Department title', 'https://inside.uams.edu'],
    ['uams', 'Department title', 'https://www.uams.edu'],
    ['institute', 'Department title', 'https://www.uams.edu'],
]);

it('accepts stringable gallery breakpoints and numeric string ratios', function (string $breakpoint, int|float $expected): void {
    expect(gallery_image_dimension(new OptionString($breakpoint), 2))->toBe($expected)
        ->and(gallery_image_dimension($breakpoint, 2, 2, '-1'))->toBe(-1);
})->with([['xxs', 337], ['xs', 337], ['sm', 433], ['md', 537], ['lg', 687], ['xl', 897.5]]);
